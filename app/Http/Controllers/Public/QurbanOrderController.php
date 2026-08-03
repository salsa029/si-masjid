<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\QurbanOrderRequest;
use App\Models\DonationSetting;
use App\Models\QurbanOrder;
use App\Models\QurbanParticipant;
use App\Models\SacrificialAnimal;
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
    ) {}

    public function create(SacrificialAnimal $sacrificialAnimal): View
    {
        abort_if($sacrificialAnimal->status !== 'available', 404);

        $takenSlots = QurbanParticipant::whereHas('order', function ($query) use ($sacrificialAnimal) {
            $query->where('sacrificial_animal_id', $sacrificialAnimal->id)
                ->whereIn('payment_status', ['pending', 'success']);
        })
            ->pluck('slot_number')
            ->toArray();

        return view('public.qurban.orders.create', compact('sacrificialAnimal', 'takenSlots'));
    }

    public function store(QurbanOrderRequest $request, SacrificialAnimal $sacrificialAnimal): RedirectResponse
    {
        $validated = $request->validated();

        $qurbanOrder = DB::transaction(function () use ($validated, $sacrificialAnimal) {
            $lockedAnimal = SacrificialAnimal::where('id', $sacrificialAnimal->id)->lockForUpdate()->first();

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

            $order = QurbanOrder::create([
                'user_id' => Auth::id(),
                'sacrificial_animal_id' => $lockedAnimal->id,
                'order_type' => $validated['order_type'],
                'total_amount' => $shareAmount,
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

        if ($qurbanOrder->payment_status === 'pending') {
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

        return view('public.qurban.orders.pay', compact('qurbanOrder', 'snapToken', 'settings'));
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

        if ($qurbanOrder->payment_status === 'pending' && $qurbanOrder->payment_method === 'midtrans') {
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
