<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\InfaqCampaign;
use App\Models\Zakat;
use App\Models\ZakatType;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class DonationDashboardController extends Controller
{
    public function index(): View
    {
        // 1. Ringkasan statistik
        $summary = [
            'total_infaq' => Infaq::where('status', 'success')->sum('amount'),
            'total_zakat' => Zakat::where('status', 'success')->sum('amount'),
            'pending_infaq_verifications' => Infaq::where('payment_method', 'manual_transfer')
                ->where('status', 'pending')
                ->count(),
            'pending_zakat_verifications' => Zakat::where('payment_method', 'manual_transfer')
                ->where('status', 'pending')
                ->count(),
        ];

        // 2. Campaign Infaq aktif
        $activeCampaigns = InfaqCampaign::where('status', 'active')
            ->withCount(['donations as collected_amount' => function ($query) {
                $query->where('status', 'success');
            }])
            ->get();

        // 3. Breakdown Zakat per jenis
        $zakatByType = Zakat::where('status', 'success')
            ->join('zakat_types', 'zakats.zakat_type_id', '=', 'zakat_types.id')
            ->selectRaw('zakat_types.name, COUNT(*) as total_transactions, SUM(zakats.amount) as total_amount')
            ->groupBy('zakat_types.name')
            ->get();

        // 4. Tren bulanan (6 bulan terakhir)
        $monthlyTrend = Infaq::where('status', 'success')
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->where('paid_at', '>=', now()->subMonths(6))
            ->whereNotNull('paid_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.donation-dashboard.index', compact(
            'summary',
            'activeCampaigns',
            'zakatByType',
            'monthlyTrend'
        ));
    }
}
