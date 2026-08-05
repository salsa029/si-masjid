<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DonationSetting;
use App\Models\Infaq;
use App\Models\InfaqCampaign;
use App\Models\InfaqCategory;
use App\Models\MosqueProfile;
use App\Services\DonationService;
use App\Services\ImageUploadService;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InfaqController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
        protected ImageUploadService $imageUploadService,
        protected DonationService $donationService,
    ) {}

    /**
     * Halaman utama Infaq
     */
    public function index(): View
    {
        $categories = InfaqCategory::orderBy('name')->get();
        $activeCampaigns = InfaqCampaign::where('status', 'active')
            ->withSum(['infaqs as collected_amount' => function ($query) {
                $query->where('payment_status', 'success');
            }], 'amount')
            ->latest()
            ->take(6)
            ->get();
        $settings = DonationSetting::firstOrNew();

        return view('public.infaq.index', compact('categories', 'activeCampaigns', 'settings'));
    }

    /**
     * Halaman detail Campaign Infaq
     */
    public function campaign(InfaqCampaign $infaqCampaign): View
    {
        return view('public.infaq.campaign', compact('infaqCampaign'));
    }

    /**
     * Halaman transparansi: riwayat seluruh infaq yang sudah berhasil, tanpa batasan tanggal
     * (semua transaksi sejak awal masjid menerima infaq lewat sistem ini). Nama yang ditampilkan
     * mengikuti pilihan donatur saat berinfaq (nama asli atau "Hamba Allah" jika anonim),
     * lewat accessor display_name pada trait HasPaymentWorkflow.
     */
    public function transparency(Request $request): View
    {
        $baseQuery = Infaq::where('payment_status', 'success')
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->input('category')));

        $infaqs = (clone $baseQuery)
            ->with(['category', 'campaign', 'user'])
            ->orderByDesc('paid_at')
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'total_amount' => (clone $baseQuery)->sum('amount'),
            'total_transactions' => (clone $baseQuery)->count(),
            'total_donors' => (clone $baseQuery)->distinct('user_id')->count('user_id'),
        ];

        $categories = InfaqCategory::orderBy('name')->get();

        return view('public.infaq.transparency', compact('infaqs', 'summary', 'categories'));
    }

    /**
     * Proses penyimpanan transaksi Infaq
     */
    public function store(Request $request): RedirectResponse
    {
        $settings = DonationSetting::firstOrNew();
        $minAmount = $settings->min_infaq_amount ?? 2000;

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:infaq_categories,id'],
            'campaign_id' => ['nullable', 'exists:infaq_campaigns,id'],
            'amount' => ['required', 'numeric', "min:{$minAmount}"],
            'is_anonymous' => ['nullable', 'boolean'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:midtrans,manual_transfer'],
        ], [
            'amount.required' => 'Nominal infaq wajib diisi.',
            'amount.min' => "Minimal infaq adalah Rp " . number_format($minAmount, 0, ',', '.') . '.',
        ]);

        $infaq = Infaq::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'campaign_id' => $validated['campaign_id'] ?? null,
            'amount' => $validated['amount'],
            'is_anonymous' => $request->boolean('is_anonymous'),
            'donor_name' => $validated['donor_name'] ?? Auth::user()->name,
            'message' => $validated['message'] ?? null,
            'midtrans_order_id' => 'INF-' . Str::uuid(),
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'reserved_until' => now()->addHours(24),
        ]);

        return redirect()->route('public.infaq.pay', $infaq);
    }

    /**
     * Halaman pembayaran Infaq
     */
    public function pay(Infaq $infaq): View
    {
        abort_unless($infaq->user_id === Auth::id(), 403);

        $snapToken = null;

        if ($infaq->payment_status === 'pending' && $infaq->payment_method === 'midtrans') {
            $snapToken = $this->midtransService->getOrCreateSnapToken(
                $infaq,
                orderId: $infaq->midtrans_order_id,
                amount: (int) round($infaq->amount),
                customerDetails: ['first_name' => Auth::user()->name, 'email' => Auth::user()->email],
                itemName: 'Infaq',
            );
        }

        $settings = DonationSetting::firstOrNew();

        return view('public.infaq.pay', compact('infaq', 'snapToken', 'settings'));
    }

    /**
     * Sinkronkan status transaksi langsung ke Midtrans (fallback selain webhook).
     * Berguna terutama saat development lokal, di mana webhook Midtrans tidak bisa
     * menjangkau localhost sehingga status tidak pernah otomatis ter-update.
     */
    public function checkStatus(Infaq $infaq): RedirectResponse
    {
        abort_unless($infaq->user_id === Auth::id(), 403);

        if ($infaq->payment_status === 'pending' && $infaq->payment_method === 'midtrans') {
            $status = $this->midtransService->getPaymentStatus($infaq->midtrans_order_id);

            if ($status === 'success') {
                $this->donationService->markAsSuccess($infaq, 'midtrans');
            } elseif ($status !== null && $status !== 'pending') {
                $infaq->update(['payment_status' => $status]);
            }
        }

        return redirect()->route('public.infaq.pay', $infaq);
    }

    /**
     * Upload bukti pembayaran manual Infaq
     */
    public function uploadProof(Request $request, Infaq $infaq): RedirectResponse
    {
        abort_unless($infaq->user_id === Auth::id(), 403);
        abort_unless($infaq->payment_method === 'manual_transfer', 404);
        abort_unless($infaq->payment_status === 'pending', 400);

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
        ]);

        $path = $this->imageUploadService->upload(
            $request->file('payment_proof'),
            folder: 'infaq-payment-proofs',
            maxWidth: 1024
        );

        $infaq->update([
            'payment_proof' => $path,
            'payment_status' => 'awaiting_verification'
        ]);

        return redirect()->route('public.infaq.pay', $infaq)
            ->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi Admin.');
    }

    /**
     * Unduh E-Kuitansi Infaq (PDF)
     */
    public function receipt(Infaq $infaq)
    {
        abort_unless($infaq->user_id === Auth::id(), 403);
        abort_unless($infaq->payment_status === 'success', 404);

        $mosqueProfile = MosqueProfile::first();

        $pdf = Pdf::loadView('pdf.infaq-receipt', compact('infaq', 'mosqueProfile'));

        return $pdf->download('e-kuitansi-' . $infaq->transaction_number . '.pdf');
    }

    /**
     * Hapus transaksi Infaq dari riwayat (hanya yang belum berhasil).
     */
    public function destroy(Request $request, Infaq $infaq): RedirectResponse
    {
        abort_unless($infaq->user_id === Auth::id(), 403);
        abort_if($infaq->payment_status === 'success', 403, 'Transaksi yang sudah berhasil tidak dapat dihapus.');

        $validated = $request->validate([
            'deletion_reason' => ['required', 'string', 'max:500'],
        ], [
            'deletion_reason.required' => 'Alasan penghapusan wajib diisi.',
        ]);

        $infaq->update(['deletion_reason' => $validated['deletion_reason']]);
        $infaq->delete();

        return redirect()->route('public.infaq.history')
            ->with('success', 'Transaksi infaq berhasil dihapus dari riwayat.');
    }

    /**
     * Halaman riwayat Infaq pengguna
     */
    public function history(Request $request): View
    {
        $infaqs = Infaq::with(['category', 'campaign'])
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('payment_status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('public.infaq.history', compact('infaqs'));
    }
}
