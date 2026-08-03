<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SacrificialAnimal;
use App\Models\MosqueProfile;
use App\Models\QurbanOrder;
use App\Services\MidtransService;
use App\Services\ImageUploadService;
use App\Services\QurbanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QurbanController extends Controller
{
    /**
     * Constructor dengan dependency injection
     *
     * @param MidtransService $midtransService
     * @param ImageUploadService $imageUploadService
     * @param QurbanService $qurbanService
     */
    public function __construct(
        protected MidtransService $midtransService,
        protected ImageUploadService $imageUploadService,
        protected QurbanService $qurbanService,
    ) {}

    /**
     * Menampilkan daftar hewan kurban yang tersedia
     *
     * @return View
     */
    public function index(): View
    {
        $sacrificialAnimals = SacrificialAnimal::where('status', '!=', 'slaughtered')
            ->withCount(['orders as booked_slots_count' => function ($query) {
                $query->where('payment_status', 'success');
            }])
            ->orderBy('animal_type')
            ->paginate(9);

        return view('public.qurban.index', compact('sacrificialAnimals'));
    }

    /**
     * Menampilkan detail hewan kurban
     *
     * @param SacrificialAnimal $sacrificialAnimal
     * @return View
     */
    public function show(SacrificialAnimal $sacrificialAnimal): View
    {
        // Load dokumentasi dengan relasi yang benar
        $sacrificialAnimal->load('documentations');

        // Hitung jumlah pesanan sukses
        $sacrificialAnimal->loadCount(['orders as booked_slots_count' => function ($query) {
            $query->where('payment_status', 'success');
        }]);

        return view('public.qurban.show', compact('sacrificialAnimal'));
    }

    /**
     * Menampilkan halaman pembayaran pesanan kurban
     *
     * @param QurbanOrder $qurbanOrder
     * @return View
     */
    public function pay(QurbanOrder $qurbanOrder): View
    {
        // Pastikan pesanan milik user yang login
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);

        $snapToken = null;

        // Snap Token hanya dibuat jika metode pembayaran Midtrans dan status pending
        if ($qurbanOrder->payment_status === 'pending' && $qurbanOrder->payment_method === 'midtrans') {
            $snapToken = $this->midtransService->createSnapToken(
                orderId: $qurbanOrder->midtrans_order_id,
                amount: (int) round($qurbanOrder->total_amount),
                customerDetails: [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                itemName: 'Kurban ' . $qurbanOrder->animal->name,
            );
        }

        return view('public.qurban.orders.pay', compact('qurbanOrder', 'snapToken'));
    }

    /**
     * Upload bukti pembayaran untuk metode transfer manual
     *
     * @param Request $request
     * @param QurbanOrder $qurbanOrder
     * @return RedirectResponse
     */
    public function uploadProof(Request $request, QurbanOrder $qurbanOrder): RedirectResponse
    {
        // Validasi 1: Pastikan pesanan milik user yang login
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);

        // Validasi 2: Pastikan metode pembayaran adalah manual transfer
        abort_unless($qurbanOrder->payment_method === 'manual_transfer', 404);

        // Validasi 3: Pastikan status pembayaran masih pending
        abort_unless($qurbanOrder->payment_status === 'pending', 400);

        // Validasi file bukti pembayaran
        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        // Upload file bukti pembayaran
        $path = $this->imageUploadService->upload(
            $request->file('payment_proof'),
            folder: 'qurban-payment-proofs'
        );

        // Update data pesanan
        $qurbanOrder->update([
            'payment_proof' => $path,
            'payment_status' => 'awaiting_verification',
        ]);

        // Redirect kembali ke halaman pembayaran dengan pesan sukses
        return redirect()
            ->route('public.qurban.orders.pay', $qurbanOrder)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu verifikasi dari Admin, maksimal 1x24 jam.');
    }

    /**
     * Download sertifikat kurban (tersedia setelah pembayaran sukses dan hewan disembelih)
     *
     * @param QurbanOrder $qurbanOrder
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function certificate(QurbanOrder $qurbanOrder)
    {
        // Validasi 1: Pastikan pesanan milik user yang login
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);

        // Validasi 2: Pastikan pembayaran sudah sukses
        abort_unless($qurbanOrder->payment_status === 'success', 404);

        // Load relasi animal beserta Qurban Activity-nya, dan user
        $qurbanOrder->load('animal.activity', 'user');

        // Validasi 3: Pastikan hewan sudah disembelih (dengan pesan custom)
        abort_unless(
            $qurbanOrder->animal->status === 'slaughtered',
            403,
            'Sertifikat tersedia setelah hewan kurban selesai disembelih.'
        );

        // Generate nomor sertifikat jika belum ada
        if (! $qurbanOrder->certificate_number) {
            $qurbanOrder->update([
                'certificate_number' => $this->qurbanService->generateCertificateNumber()
            ]);
            // Refresh data agar certificate_number terbaru terbaca
            $qurbanOrder->refresh();
        }

        // Generate QR code verifikasi (mengarah ke halaman verifikasi publik)
        $verificationUrl = route('public.qurban.certificate-verify', $qurbanOrder->certificate_number);
        $qrCode = new QrCode(data: $verificationUrl, size: 180, margin: 4);
        $qrCodeDataUri = (new PngWriter())->write($qrCode)->getDataUri();

        // Embed TTD Ketua DKM & foto Ketua Kurban dari Qurban Activity sebagai base64 (agar tampil stabil di PDF)
        $activity = $qurbanOrder->animal->activity;
        $dkmSignatureDataUri = $this->imageToDataUri($activity?->dkm_chairman_signature);
        $qurbanChairmanPhotoDataUri = $this->imageToDataUri($activity?->qurban_chairman_photo);
        $mosqueProfile = MosqueProfile::first();

        // Hitung tahun Hijriah otomatis dari tanggal Qurban Activity (atau tanggal hari ini jika tidak ada activity)
        $hijriFormatter = new \IntlDateFormatter(
            'id_ID@calendar=islamic',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            null,
            \IntlDateFormatter::TRADITIONAL,
            'yyyy'
        );
        $hijriYear = $hijriFormatter->format(($activity?->date ?? now())->getTimestamp());

        // Latar belakang sertifikat (desain dari Canva), di-embed sebagai base64
        $backgroundDataUri = 'data:image/png;base64,' . base64_encode(
            file_get_contents(resource_path('certificate-assets/qurban-certificate-bg.png'))
        );

        // Generate PDF sertifikat
        $pdf = Pdf::loadView('pdf.qurban-certificate', compact(
            'qurbanOrder',
            'qrCodeDataUri',
            'dkmSignatureDataUri',
            'qurbanChairmanPhotoDataUri',
            'mosqueProfile',
            'hijriYear',
            'backgroundDataUri'
        ))->setPaper('a4', 'landscape');

        // Tampilkan PDF di browser (stream)
        return $pdf->stream('sertifikat-kurban-' . $qurbanOrder->certificate_number . '.pdf');
    }

    /**
     * Mengubah file gambar di storage public menjadi data URI base64,
     * agar tampil stabil saat dirender oleh dompdf.
     */
    protected function imageToDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return 'data:image/webp;base64,' . base64_encode(Storage::disk('public')->get($path));
    }

    /**
     * Halaman verifikasi publik sertifikat kurban (diakses via QR code, tanpa perlu login).
     * Hanya menampilkan info non-sensitif untuk memastikan keaslian sertifikat.
     */
    public function verifyCertificate(string $certificateNumber): View
    {
        $qurbanOrder = QurbanOrder::with(['animal.activity', 'user'])
            ->where('certificate_number', $certificateNumber)
            ->where('payment_status', 'success')
            ->first();

        return view('public.qurban.certificate-verify', compact('qurbanOrder', 'certificateNumber'));
    }

    /**
     * Download kuitansi pembayaran (sudah ada sebelumnya)
     *
     * @param QurbanOrder $qurbanOrder
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function receipt(QurbanOrder $qurbanOrder)
    {
        // Pastikan pesanan milik user yang login
        abort_unless($qurbanOrder->user_id === Auth::id(), 403);

        // Pastikan pembayaran sudah sukses
        abort_unless($qurbanOrder->payment_status === 'success', 404);

        // Load relasi animal
        $qurbanOrder->load('animal');

        // Generate PDF kuitansi
        $pdf = Pdf::loadView('pdf.qurban.receipt', compact('qurbanOrder'));

        // Download PDF (bukan stream/tampil di browser)
        return $pdf->download('e-kuitansi-' . $qurbanOrder->midtrans_order_id . '.pdf');
    }
}
