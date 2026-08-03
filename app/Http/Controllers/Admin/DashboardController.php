<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\Infaq;           // ✅ BARU
use App\Models\QurbanOrder;
use App\Models\SacrificialAnimal;
use App\Models\User;
use App\Models\Zakat;           // ✅ BARU
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_jamaah'   => \App\Models\User::role('jamaah')->count(),
            'total_artikel'  => \App\Models\Article::count(),
            'total_kegiatan' => \App\Models\Event::count(),
            'total_infaq'    => \App\Models\Infaq::success()->sum('amount'),
            'total_zakat'    => \App\Models\Zakat::success()->sum('amount'),
        ];

        $monthlyFunds = collect(range(0, 5))->map(function ($i) {
            $month = now()->subMonths($i);

            return [
                'label' => $month->translatedFormat('F Y'),
                'infaq' => \App\Models\Infaq::success()->whereYear('paid_at', $month->year)->whereMonth('paid_at', $month->month)->sum('amount'),
                'zakat' => \App\Models\Zakat::success()->whereYear('paid_at', $month->year)->whereMonth('paid_at', $month->month)->sum('amount'),
            ];
        })->reverse()->values();

        $qurbanSummary = [
            'total_animals'     => \App\Models\SacrificialAnimal::count(),
            'available'         => \App\Models\SacrificialAnimal::where('status', 'available')->count(),
            'fully_booked'      => \App\Models\SacrificialAnimal::where('status', 'fully_booked')->count(),
            'slaughtered'       => \App\Models\SacrificialAnimal::where('status', 'slaughtered')->count(),
            'successful_orders' => \App\Models\QurbanOrder::where('payment_status', 'success')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'monthlyFunds', 'qurbanSummary'));
    }
}
