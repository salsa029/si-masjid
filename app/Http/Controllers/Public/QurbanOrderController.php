<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\QurbanOrderRequest;
use App\Models\DonationSetting;
use App\Models\QurbanInstallment;
use App\Models\QurbanOrder;
use App\Models\QurbanParticipant;
use App\Models\SacrificialAnimal;
use App\Services\ImageUploadService;
use App\Services\MidtransService;
use App\Services\QurbanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\Request;

class QurbanOrderController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
        protected QurbanService $qurbanService,
        protected ImageUploadService $imageUploadService,
    ) {}

    public function create(SacrificialAnimal $sacrificialAnimal): View
    {
        abort_if($sacrificialAnimal->status !== 'available', 404);
        abort_if(
            $sacrificialAnimal->activity && ! $sacrificialAnimal->activity->isRegistrationOpen(),
            404,
            'Batas waktu pendaftaran kurban untuk kegiatan ini sudah berakhir.'
        );

        $takenSlots = QurbanParticipant::whereHas('order', function ($query) use ($sacrificialAnimal) {
            $query->where('sacrificial_animal_id', $sacrificialAnimal->id)
                ->whereIn('payment_status', ['pending', 'success']);
        })
            ->pluck('slot_number')
            ->toArray();

        // Cicilan hanya tersedia jika hewan terikat Qurban Activity dengan batas waktu masih tersisa
        // minimal 2 hari lagi (supaya ada jarak yang wajar untuk minimal 2x cicilan).
        $installmentDeadline = $sacrificialAnimal->activity?->end_date;
        $installmentEligible = $installmentDeadline !== null && $installmentDeadline->gte(now()->addDays(2)->startOfDay());

        return view('public.qurban.orders.create', compact('sacrificialAnimal', 'takenSlots', 'installmentEligible', 'installmentDeadline'));
    }

    public function store(QurbanOrderRequest $request, SacrificialAnimal $sacrificialAnimal): RedirectResponse
    {
        $validated = $request->validated();

        $qurbanOrder = DB::transaction(function () use ($validated, $sacrificialAnimal) {
            $lockedAnimal = SacrificialAnimal::where('id', $sacrificialAnimal->id)->lockForUpdate()->first();

            if ($lockedAnimal->activity && ! $lockedAnimal->activity->isRegistrationOpen()) {
                throw ValidationException::withMessages([
                    'order_type' => 'Batas waktu pendaftaran kurban untuk kegiatan ini sudah berakhir.',
                ]);
            }

            // Cegah oversell: pesanan 'full' (beli sendirian) tidak boleh dibuat kalau hewan ini
            // sudah punya pesanan apapun (penuh maupun patungan), dan sebaliknya pesanan 'patungan'
            // tidak boleh dibuat kalau hewan ini sudah dibeli penuh oleh orang lain.
            if ($validated['order_type'] === 'full') {
                $animalAlreadyTaken = QurbanOrder::where('sacrificial_animal_id', $lockedAnimal->id)
                    ->whereIn('payment_status', ['pending', 'success'])
                    ->exists();

                if ($animalAlreadyTaken) {
                    throw ValidationException::withMessages([
                        'order_type' => 'Mohon maaf, hewan ini sudah ada yang memesan (penuh maupun patungan). Silakan pilih hewan lain.',
                    ]);
                }
            } else {
                $hasFullOrder = QurbanOrder::where('sacrificial_animal_id', $lockedAnimal->id)
                    ->whereIn('payment_status', ['pending', 'success'])
                    ->where('order_type', 'full')
                    ->exists();

                if ($hasFullOrder) {
                    throw ValidationException::withMessages([
                        'order_type' => 'Mohon maaf, hewan ini sudah dibeli penuh oleh Jamaah lain.',
                    ]);
                }
            }

            $slotNumber = $validated['order_type'] === 'patungan' ? (int) $validated['slot_number'] : 1;

            $slotAlreadyTaken = QurbanParticipant::whereHas('order', function ($query) use ($lockedAnimal) {
                $query->where('sacrificial_animal_id', $lockedAnimal->id)
                    ->whereIn('payment_status', ['pending', 'success']);
            })
                ->where('slot_number', $slotNumber)
                ->exists();

            if ($slotAlreadyTaken) {
                throw ValidationException::withMessages([
                    'slot_number' => 'Mohon maaf, slot ini baru saja dipesan oleh Jamaah lain. Silakan pilih slot lain.',
                ]);
            }

            $shareAmount = $validated['order_type'] === 'patungan'
                ? round($lockedAnimal->price / $lockedAnimal->max_participants)
                : $lockedAnimal->price;

            $isInstallment = $validated['payment_type'] === 'installment';

            $order = QurbanOrder::create([
                'user_id' => Auth::id(),
                'sacrificial_animal_id' => $lockedAnimal->id,
                'order_type' => $validated['order_type'],
                'total_amount' => $shareAmount,
                'payment_type' => $validated['payment_type'],
                'installment_count' => $isInstallment ? (int) $validated['installment_count'] : null,
                'installment_deadline' => $isInstallment ? $lockedAnimal->activity->end_date : null,
                'midtrans_order_id' => 'QRB-' . Str::uuid(),
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'reserved_until' => now()->addHours(24),
            ]);

            QurbanParticipant::create([
                'qurban_order_id' => $order->id,
                'user_id' => Auth::id(), // Aman untuk guest
                'slot_number' => $slotNumber,
                'share_amount' => $shareAmount,
            ]);

            if ($isInstallment) {
                $this->qurbanService->createInstallmentPlan($order, (int) $validated['installment_count'], $lockedAnimal->activity->end_date);
            }

            return $order;
        });

        return redirect()->route('public.qurban.orders.pay', $qurbanOrder);
    }

    public function pay(QurbanOrder $qurbanOrder): View
    {
        // Hanya batasi jika order terikat pada user_id tertentu
        if ($qurbanOrder->user_id) {
            abort_unless($qurbanOrder->user_id === Auth::id(), 403);
        }

        $snapToken = null;
        $nextInstallment = null;

        if ($qurbanOrder->isInstallment()) {
            $nextInstallment = $qurbanOrder->next_installment;

            if (
                $nextInstallment
                && $qurbanOrder->payment_method === 'midtrans'
                && in_array($nextInstallment->payment_status, ['pending', 'failed'], true)
            ) {
                $snapToken = $this->midtransService->getOrCreateSnapToken(
                    $nextInstallment,
                    orderId: $nextInstallment->midtrans_order_id,
                    amount: (int) round($nextInstallment->amount),
                    customerDetails: [
                        'first_name' => Auth::user()?->name ?? 'Shohibul Qurban',
                        'email' => Auth::user()?->email ?? 'guest@example.com',
                    ],
                    itemName: 'Cicilan ke-' . $nextInstallment->installment_number . ' Kurban ' . $qurbanOrder->animal->name,
                );
            }
        } elseif ($qurbanOrder->payment_status === 'pending') {
            $snapToken = $this->midtransService->getOrCreateSnapToken(
                $qurbanOrder,
                orderId: $qurbanOrder->midtrans_order_id,
                amount: (int) round($qurbanOrder->total_amount),
                customerDetails: [
                    'first_name' => Auth::user()?->name ?? 'Shohibul Qurban',
                    'email' => Auth::user()?->email ?? 'guest@example.com',
                ],
                itemName: 'Kurban ' . $qurbanOrder->animal->name,
            );
        }

        $settings = DonationSetting::firstOrNew();

        return view('public.qurban.orders.pay', compact('qurbanOrder', 'snapToken', 'nextInstallment', 'settings'));
    }

    /**
     * Sinkronkan status transaksi langsung ke Midtrans (fallback selain webhook).
     * Berguna terutama saat development lokal, di mana webhook Midtrans tidak bisa
     * menjangkau localhost sehingga status tidak pernah otomatis ter-update.
     */
    public function checkStatus(QurbanOrder $qurbanOrder): RedirectResponse
    {
        if ($qurbanOrder->user_id) {
            abort_unless($qurbanOrder->user_id === Auth::id(), 403);
        }

        if ($qurbanOrder->payment_method !== 'midtrans') {
            return redirect()->route('public.qurban.orders.pay', $qurbanOrder);
        }

        if ($qurbanOrder->isInstallment()) {
            $nextInstallment = $qurbanOrder->next_installment;

            if ($nextInstallment) {
                $status = $this->midtransService->getPaymentStatus($nextInstallment->midtrans_order_id);

                if ($status === 'success') {
                    $this->qurbanService->markInstallmentAsSuccess($nextInstallment, 'midtrans');
                } elseif ($status !== null && $status !== 'pending') {
                    // 'failed' maupun 'expired' dari Midtrans -> cicilan ini gagal, jamaah bisa bayar ulang
                    // (beda dengan payment_status pesanan penuh, cicilan tidak punya status 'expired' sendiri).
                    $nextInstallment->update(['payment_status' => 'failed']);
                }
            }
        } elseif ($qurbanOrder->payment_status === 'pending') {
            $status = $this->midtransService->getPaymentStatus($qurbanOrder->midtrans_order_id);

            if ($status === 'success') {
                $this->qurbanService->markOrderAsSuccess($qurbanOrder, 'midtrans');
            } elseif ($status !== null && $status !== 'pending') {
                $qurbanOrder->update(['payment_status' => $status]);
            }
        }

        return redirect()->route('public.qurban.orders.pay', $qurbanOrder);
    }

    /**
     * Upload bukti transfer manual untuk satu cicilan tertentu.
     */
    public function uploadInstallmentProof(Request $request, QurbanOrder $qurbanOrder, QurbanInstallment $installment): RedirectResponse
    {
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);
        abort_unless($installment->qurban_order_id === $qurbanOrder->id, 404);
        abort_unless($qurbanOrder->payment_method === 'manual_transfer', 404);
        abort_unless(in_array($installment->payment_status, ['pending', 'failed'], true), 400);

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
        ]);

        $path = $this->imageUploadService->upload(
            $request->file('payment_proof'),
            folder: 'qurban-payment-proofs'
        );

        $installment->update([
            'payment_proof' => $path,
            'payment_status' => 'awaiting_verification',
        ]);

        return redirect()
            ->route('public.qurban.orders.pay', $qurbanOrder)
            ->with('success', 'Bukti pembayaran cicilan ke-' . $installment->installment_number . ' berhasil diunggah. Mohon tunggu verifikasi dari Admin.');
    }

    /**
     * Jamaah mengajukan permintaan refund untuk dana cicilan yang sudah dibayar, sebelum batas
     * waktu pelunasan. Kalau tidak diajukan, dana yang sudah masuk otomatis dialihkan jadi infaq
     * apabila cicilan tidak lunas hingga batas waktu.
     */
    public function requestRefund(QurbanOrder $qurbanOrder): RedirectResponse
    {
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);
        abort_unless($qurbanOrder->isInstallment(), 404);
        abort_unless($qurbanOrder->payment_status === 'pending', 400);
        abort_if($qurbanOrder->refund_requested, 400, 'Permintaan refund untuk pesanan ini sudah pernah diajukan.');

        $qurbanOrder->update([
            'refund_requested' => true,
            'refund_requested_at' => now(),
        ]);

        return redirect()
            ->route('public.qurban.orders.pay', $qurbanOrder)
            ->with('success', 'Permintaan refund berhasil dicatat. Jika cicilan tidak lunas hingga batas waktu, dana yang sudah dibayar akan dikembalikan oleh Admin (bukan dialihkan menjadi infaq).');
    }

    /**
     * Hapus pesanan Kurban dari riwayat (hanya yang belum berhasil).
     */
    public function destroy(Request $request, QurbanOrder $qurbanOrder): RedirectResponse
    {
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);
        abort_if($qurbanOrder->payment_status === 'success', 403, 'Pesanan yang sudah berhasil tidak dapat dihapus.');

        $validated = $request->validate([
            'deletion_reason' => ['required', 'string', 'max:500'],
        ], [
            'deletion_reason.required' => 'Alasan penghapusan wajib diisi.',
        ]);

        $qurbanOrder->update(['deletion_reason' => $validated['deletion_reason']]);
        $qurbanOrder->delete();

        return redirect()->route('public.qurban.orders.history')
            ->with('success', 'Pesanan kurban berhasil dihapus dari riwayat.');
    }

    public function history(Request $request): View
    {
        $qurbanOrders = QurbanOrder::with('animal')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('payment_status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('public.qurban.orders.history', compact('qurbanOrders'));
    }
}
