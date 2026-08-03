<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DonationSetting;
use App\Models\MosqueProfile;
use App\Models\Zakat;
use App\Models\ZakatType;
use App\Services\DonationService;
use App\Services\ImageUploadService;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ZakatController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
        protected ImageUploadService $imageUploadService,
        protected DonationService $donationService,
    ) {}

    /**
     * Halaman utama Zakat (dengan kalkulator terintegrasi)
     */
    public function index(): View
    {
        $zakatTypes = ZakatType::orderBy('name')->get();
        $settings = DonationSetting::first();

        return view('public.zakat.index', compact('zakatTypes', 'settings'));
    }

    /**
     * Proses penyimpanan transaksi Zakat
     */
    public function store(Request $request): RedirectResponse
    {
        $settings = DonationSetting::first();
        $minAmount = $settings->min_zakat_amount ?? 10000;

        $validated = $request->validate([
            'zakat_type_id' => ['required', 'exists:zakat_types,id'],
            'amount' => ['required', 'numeric', "min:{$minAmount}"],
            'calculation_base' => ['nullable', 'numeric', 'min:0'],
            'number_of_souls' => ['nullable', 'integer', 'min:1'],
            'is_above_nishab' => ['nullable', 'boolean'],
            'muzakki_name' => ['nullable', 'string', 'max:255'],
            'is_anonymous' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:midtrans,manual_transfer'],
        ], [
            'zakat_type_id.required' => 'Pilih jenis zakat terlebih dahulu.',
            'amount.required' => 'Nominal zakat wajib diisi.',
            'amount.min' => "Minimal zakat adalah Rp " . number_format($minAmount, 0, ',', '.') . '.',
        ]);

        $zakat = Zakat::create([
            'user_id' => Auth::id(),
            'zakat_type_id' => $validated['zakat_type_id'],
            'amount' => $validated['amount'],
            'calculation_base' => $validated['calculation_base'] ?? null,
            'number_of_souls' => $validated['number_of_souls'] ?? null,
            'is_above_nishab' => $request->has('is_above_nishab') ? $request->boolean('is_above_nishab') : null,
            'muzakki_name' => $validated['muzakki_name'] ?? Auth::user()->name,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'message' => $validated['message'] ?? null,
            'midtrans_order_id' => 'ZKT-' . Str::uuid(),
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'reserved_until' => now()->addHours(24),
        ]);

        return redirect()->route('public.zakat.pay', $zakat);
    }

    /**
     * Halaman pembayaran Zakat
     */
    public function pay(Zakat $zakat): View
    {
        abort_unless($zakat->user_id === Auth::id(), 403);

        $snapToken = null;

        if ($zakat->payment_status === 'pending' && $zakat->payment_method === 'midtrans') {
            $snapToken = $this->midtransService->createSnapToken(
                orderId: $zakat->midtrans_order_id,
                amount: (int) round($zakat->amount),
                customerDetails: ['first_name' => Auth::user()->name, 'email' => Auth::user()->email],
                itemName: 'Zakat — ' . $zakat->zakatType->name,
            );
        }

        $settings = DonationSetting::first();

        return view('public.zakat.pay', compact('zakat', 'snapToken', 'settings'));
    }

    /**
     * Upload bukti pembayaran manual Zakat
     */
    public function uploadProof(Request $request, Zakat $zakat): RedirectResponse
    {
        abort_unless($zakat->user_id === Auth::id(), 403);
        abort_unless($zakat->payment_method === 'manual_transfer', 404);
        abort_unless($zakat->payment_status === 'pending', 400);

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
        ]);

        $path = $this->imageUploadService->upload(
            $request->file('payment_proof'),
            folder: 'zakat-payment-proofs',
            maxWidth: 1024
        );

        $zakat->update([
            'payment_proof' => $path,
            'payment_status' => 'awaiting_verification'
        ]);

        return redirect()->route('public.zakat.pay', $zakat)
            ->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi Admin.');
    }

    /**
     * Unduh E-Kuitansi Zakat (PDF)
     */
    public function receipt(Zakat $zakat)
    {
        abort_unless($zakat->user_id === Auth::id(), 403);
        abort_unless($zakat->payment_status === 'success', 404);

        $zakat->load('zakatType');
        $mosqueProfile = MosqueProfile::first();

        $pdf = Pdf::loadView('pdf.zakat-receipt', compact('zakat', 'mosqueProfile'));

        return $pdf->download('e-kuitansi-' . $zakat->transaction_number . '.pdf');
    }

    /**
     * Halaman riwayat Zakat pengguna
     */
    public function history(Request $request): View
    {
        $zakats = Zakat::with('zakatType')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('payment_status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('public.zakat.history', compact('zakats'));
    }
}
