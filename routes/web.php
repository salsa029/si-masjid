<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\WebhookController;

// Kontroler Publik
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CommitteeController as PublicCommitteeController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;
use App\Http\Controllers\Public\EventController as PublicEventController;
use App\Http\Controllers\Public\QurbanController;
use App\Http\Controllers\Public\QurbanOrderController;
use App\Http\Controllers\Public\InfaqController as PublicInfaqController;
use App\Http\Controllers\Public\ZakatController as PublicZakatController;

// Kontroler Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MosqueProfileController;
use App\Http\Controllers\Admin\MosqueGalleryImageController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventDocumentationController;
use App\Http\Controllers\Admin\QurbanActivityController;
use App\Http\Controllers\Admin\SacrificialAnimalController;
use App\Http\Controllers\Admin\SlaughterDocumentationController;
use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\QurbanVerificationController;
use App\Http\Controllers\Admin\QurbanDashboardController;
use App\Http\Controllers\Admin\DonationDashboardController;
use App\Http\Controllers\Admin\DonationSettingController;
use App\Http\Controllers\Admin\DonationVerificationController;
use App\Http\Controllers\Admin\InfaqCategoryController;
use App\Http\Controllers\Admin\InfaqCampaignController;
use App\Http\Controllers\Admin\InfaqController;
use App\Http\Controllers\Admin\ZakatTypeController;
use App\Http\Controllers\Admin\ZakatController;
use App\Http\Controllers\Admin\UserController;

// ============================================
// PRAYER TIMES API CONTROLLER
// ============================================
use App\Http\Controllers\Api\PrayerTimeController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE PRAYER TIMES API (REAL-TIME)
|--------------------------------------------------------------------------
*/

Route::prefix('api/prayer')->name('api.prayer.')->group(function () {
    Route::get('/times', [PrayerTimeController::class, 'index'])->name('times');
    Route::post('/refresh', [PrayerTimeController::class, 'refresh'])->name('refresh');
    Route::get('/cities', [PrayerTimeController::class, 'cities'])->name('cities');
});

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (Bisa Diakses Semua Orang)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pengurus', [PublicCommitteeController::class, 'index'])->name('public.committees.index');

// Rute Publik Artikel
Route::get('/artikel', [PublicArticleController::class, 'index'])->name('public.articles.index');
Route::get('/artikel/{slug}', [PublicArticleController::class, 'show'])->name('public.articles.show');

// Rute Publik Kegiatan
Route::get('/kegiatan', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/kegiatan/{slug}', [PublicEventController::class, 'show'])->name('public.events.show');

// Rute Publik Kalender Kegiatan
Route::get('/kalender-kegiatan', [PublicEventController::class, 'calendar'])->name('public.events.calendar');
Route::get('/kalender-kegiatan/data', [PublicEventController::class, 'calendarData'])->name('public.events.calendar-data');

// Rute Publik Kurban
Route::get('/kurban', [QurbanController::class, 'index'])->name('public.qurban.index');
Route::get('/sertifikat-kurban/verifikasi/{certificateNumber}', [QurbanController::class, 'verifyCertificate'])
    ->name('public.qurban.certificate-verify');
Route::get('/kurban/{sacrificialAnimal}', [QurbanController::class, 'show'])->name('public.qurban.show');

// Rute Publik Infaq & Zakat
Route::get('/infaq', [PublicInfaqController::class, 'index'])->name('public.infaq.index');
Route::get('/infaq/campaign/{infaqCampaign:slug}', [PublicInfaqController::class, 'campaign'])->name('public.infaq.campaign');
Route::get('/zakat', [PublicZakatController::class, 'index'])->name('public.zakat.index');

// Redirect calculator ke halaman utama zakat
Route::get('/zakat/kalkulator', function () {
    return redirect()->route('public.zakat.index');
})->name('public.zakat.calculator');

/*
|--------------------------------------------------------------------------
| Rute Login Google & Webhook
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Webhook Midtrans - Dilindungi Rate Limiting & Tanpa Auth
Route::post('/webhook/midtrans', [WebhookController::class, 'handle'])
    ->name('webhook.midtrans')
    ->middleware('throttle:60,1')
    ->withoutMiddleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Rute Bawaan Breeze (Login Manual, Register, Verifikasi Email, Reset Password)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Rute Khusus Jamaah / User yang Sudah Login & Terverifikasi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // ============================================
    // MODUL INFAQ (PUBLIK) - LENGKAP
    // ============================================
    Route::prefix('infaq')->name('public.infaq.')->group(function () {
        Route::post('/', [PublicInfaqController::class, 'store'])->name('store');
        Route::get('/riwayat', [PublicInfaqController::class, 'history'])->name('history');
        Route::get('/{infaq}/bayar', [PublicInfaqController::class, 'pay'])->name('pay');
        Route::get('/{infaq}/cek-status', [PublicInfaqController::class, 'checkStatus'])->name('check-status');
        Route::get('/{infaq}/kuitansi', [PublicInfaqController::class, 'receipt'])->name('receipt');
        Route::post('/{infaq}/upload-proof', [PublicInfaqController::class, 'uploadProof'])->name('upload-proof');
        Route::delete('/{infaq}', [PublicInfaqController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // MODUL ZAKAT (PUBLIK) - LENGKAP
    // ============================================
    Route::prefix('zakat')->name('public.zakat.')->group(function () {
        Route::post('/', [PublicZakatController::class, 'store'])->name('store');
        Route::get('/riwayat', [PublicZakatController::class, 'history'])->name('history');
        Route::get('/{zakat}/bayar', [PublicZakatController::class, 'pay'])->name('pay');
        Route::get('/{zakat}/cek-status', [PublicZakatController::class, 'checkStatus'])->name('check-status');
        Route::get('/{zakat}/kuitansi', [PublicZakatController::class, 'receipt'])->name('receipt');
        Route::post('/{zakat}/upload-proof', [PublicZakatController::class, 'uploadProof'])->name('upload-proof');
        Route::delete('/{zakat}', [PublicZakatController::class, 'destroy'])->name('destroy');
    });

    // ============================================
    // MODUL KURBAN (PUBLIK) - LENGKAP
    // ============================================
    Route::prefix('kurban')->name('public.qurban.')->group(function () {
        Route::get('/{sacrificialAnimal}/pesan', [QurbanOrderController::class, 'create'])->name('orders.create');
        Route::post('/{sacrificialAnimal}/pesan', [QurbanOrderController::class, 'store'])->name('orders.store');
    });

    Route::prefix('pesanan-kurban')->name('public.qurban.orders.')->group(function () {
        Route::get('/saya', [QurbanOrderController::class, 'history'])->name('history');
        Route::get('/{qurbanOrder}/bayar', [QurbanOrderController::class, 'pay'])->name('pay');
        Route::get('/{qurbanOrder}/cek-status', [QurbanOrderController::class, 'checkStatus'])->name('check-status');
        Route::get('/{qurbanOrder}/kuitansi', [QurbanController::class, 'receipt'])->name('receipt');
        Route::post('/{qurbanOrder}/bukti-pembayaran', [QurbanController::class, 'uploadProof'])->name('upload-proof');
        Route::get('/{qurbanOrder}/sertifikat', [QurbanController::class, 'certificate'])->name('certificate');
        Route::delete('/{qurbanOrder}', [QurbanOrderController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Rute Manajemen Profil User (Bawaan Project Anda)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Admin (Menggunakan Spatie Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Dashboard Khusus Donasi & Kurban (ADMIN)
        Route::get('/donation-dashboard', [DonationDashboardController::class, 'index'])->name('donation-dashboard.index');
        Route::get('/qurban-dashboard', [QurbanDashboardController::class, 'index'])->name('qurban-dashboard.index');

        // Rute Kelola Profil Masjid
        Route::get('/mosque-profile', [MosqueProfileController::class, 'edit'])->name('mosque-profile.edit');
        Route::put('/mosque-profile', [MosqueProfileController::class, 'update'])->name('mosque-profile.update');
        Route::post('/mosque-profile/gallery', [MosqueGalleryImageController::class, 'store'])->name('mosque-profile.gallery.store');
        Route::delete('/mosque-profile/gallery/{galleryImage}', [MosqueGalleryImageController::class, 'destroy'])->name('mosque-profile.gallery.destroy');

        // Rute Kelola Pengurus
        Route::resource('committees', CommitteeController::class)->except(['show']);
        Route::patch('/committees/{committee}/move-up', [CommitteeController::class, 'moveUp'])->name('committees.move-up');
        Route::patch('/committees/{committee}/move-down', [CommitteeController::class, 'moveDown'])->name('committees.move-down');

        // Rute Kelola Kategori Artikel
        Route::resource('article-categories', ArticleCategoryController::class)->except(['show']);

        // Rute Kelola Kategori Kegiatan
        Route::resource('event-categories', EventCategoryController::class)->except(['show']);

        // Rute Kelola Trash (Soft Deletes) & Artikel
        Route::get('/articles/trash', [ArticleController::class, 'trash'])->name('articles.trash');
        Route::put('/articles/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');
        Route::delete('/articles/{id}/force-delete', [ArticleController::class, 'forceDelete'])->name('articles.force-delete');
        Route::resource('articles', ArticleController::class)->except(['show']);

        // Rute Kelola Kegiatan & Dokumentasi Kegiatan
        Route::resource('events', EventController::class)->except(['show']);
        Route::post('/events/{event}/documentations', [EventDocumentationController::class, 'store'])
            ->name('events.documentations.store');
        Route::delete('/events/{event}/documentations/{documentation}', [EventDocumentationController::class, 'destroy'])
            ->name('events.documentations.destroy');

        // Rute Kelola Qurban Activity (kegiatan/tahun kurban)
        Route::resource('qurban-activities', QurbanActivityController::class)->except(['show']);

        // Rute Kelola Hewan Kurban & Dokumentasi Penyembelihan
        Route::resource('sacrificial-animals', SacrificialAnimalController::class)->except(['show']);
        Route::post('/sacrificial-animals/{sacrificialAnimal}/documentations', [SlaughterDocumentationController::class, 'store'])
            ->name('sacrificial-animals.documentations.store');
        Route::delete('/sacrificial-animals/{sacrificialAnimal}/documentations/{documentation}', [SlaughterDocumentationController::class, 'destroy'])
            ->name('sacrificial-animals.documentations.destroy');

        // Rute Verifikasi Pemesanan Kurban (ADMIN)
        Route::prefix('qurban-verifications')->name('qurban-verifications.')->group(function () {
            Route::get('/', [QurbanVerificationController::class, 'index'])->name('index');
            Route::get('/{qurbanOrder}', [QurbanVerificationController::class, 'show'])->name('show');
            Route::put('/{qurbanOrder}/approve', [QurbanVerificationController::class, 'approve'])->name('approve');
            Route::put('/{qurbanOrder}/reject', [QurbanVerificationController::class, 'reject'])->name('reject');
        });

        // Modul Manajemen Infaq (ADMIN)
        Route::resource('infaq-categories', InfaqCategoryController::class)->except(['show']);
        Route::resource('infaq-campaigns', InfaqCampaignController::class)->except(['show']);
        Route::resource('infaqs', InfaqController::class)->only(['index', 'show']);

        // Modul Manajemen Zakat (ADMIN)
        Route::resource('zakat-types', ZakatTypeController::class)->except(['show']);
        Route::resource('zakats', ZakatController::class)->only(['index', 'show']);

        // Modul Verifikasi Donasi (ADMIN)
        Route::prefix('donation-verifications')->name('donation-verifications.')->group(function () {
            // Route Verifikasi Infaq
            Route::get('/infaq', [DonationVerificationController::class, 'infaqIndex'])->name('infaq.index');
            Route::get('/infaq/{infaq}', [DonationVerificationController::class, 'infaqShow'])->name('infaq.show');
            Route::put('/infaq/{infaq}/approve', [DonationVerificationController::class, 'infaqApprove'])->name('infaq.approve');
            Route::put('/infaq/{infaq}/reject', [DonationVerificationController::class, 'infaqReject'])->name('infaq.reject');

            // Route Verifikasi Zakat
            Route::get('/zakat', [DonationVerificationController::class, 'zakatIndex'])->name('zakat.index');
            Route::get('/zakat/{zakat}', [DonationVerificationController::class, 'zakatShow'])->name('zakat.show');
            Route::put('/zakat/{zakat}/approve', [DonationVerificationController::class, 'zakatApprove'])->name('zakat.approve');
            Route::put('/zakat/{zakat}/reject', [DonationVerificationController::class, 'zakatReject'])->name('zakat.reject');
        });

        // Rute Laporan Keuangan
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index'])->name('index');
            Route::get('/export-excel', [FinancialReportController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [FinancialReportController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export-csv', [FinancialReportController::class, 'exportCsv'])->name('export-csv');
        });

        // Rute Audit Trail
        Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail.index');

        // Rute Pengaturan Donasi (Infaq & Zakat)
        Route::get('/donation-settings', [DonationSettingController::class, 'edit'])->name('donation-settings.edit');
        Route::put('/donation-settings', [DonationSettingController::class, 'update'])->name('donation-settings.update');

        // Rute Kelola User & Admin
        Route::resource('users', UserController::class)->except(['show']);
    });

/*
|--------------------------------------------------------------------------
| Fallback Route (Menangani 404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
