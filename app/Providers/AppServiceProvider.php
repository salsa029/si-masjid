<?php

namespace App\Providers;

use App\Models\Infaq;
use App\Models\QurbanInstallment;
use App\Models\QurbanOrder;
use App\Models\Zakat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('admin.partials.sidebar', function ($view) {
            $pendingInfaqVerifications = Infaq::where('payment_method', 'manual_transfer')
                ->where('payment_status', 'awaiting_verification')
                ->count();
            $pendingZakatVerifications = Zakat::where('payment_method', 'manual_transfer')
                ->where('payment_status', 'awaiting_verification')
                ->count();
            $pendingQurbanVerifications = QurbanOrder::where('payment_method', 'manual_transfer')
                ->where('payment_status', 'awaiting_verification')
                ->count();
            $pendingQurbanInstallmentVerifications = QurbanInstallment::where('payment_status', 'awaiting_verification')
                ->count();

            $view->with([
                'pendingInfaqVerifications' => $pendingInfaqVerifications,
                'pendingZakatVerifications' => $pendingZakatVerifications,
                'pendingQurbanVerifications' => $pendingQurbanVerifications,
                'pendingQurbanInstallmentVerifications' => $pendingQurbanInstallmentVerifications,
                // Total gabungan untuk badge notifikasi di judul grup menu (Donasi & Infaq / Kurban),
                // supaya admin bisa lihat ada verifikasi menunggu tanpa harus buka dulu submenu-nya.
                'donasiPendingVerifications' => $pendingInfaqVerifications + $pendingZakatVerifications,
                'kurbanPendingVerifications' => $pendingQurbanVerifications + $pendingQurbanInstallmentVerifications,
            ]);
        });
    }
}
