<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\Infaq;
use App\Models\QurbanOrder;
use App\Models\SacrificialAnimal;
use App\Models\User;
use App\Models\Zakat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_jamaah'   => User::role('jamaah')->count(),
            'total_artikel'  => Article::count(),
            'total_kegiatan' => Event::count(),
            'total_infaq'    => Infaq::success()->sum('amount'),
            'total_zakat'    => Zakat::success()->sum('amount'),
        ];

        $rangeStart = now()->subMonths(5)->startOfMonth();
        $infaqByMonth = Infaq::success()
            ->where('paid_at', '>=', $rangeStart)
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');
        $zakatByMonth = Zakat::success()
            ->where('paid_at', '>=', $rangeStart)
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyFunds = collect(range(0, 5))->map(function ($i) use ($infaqByMonth, $zakatByMonth) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');

            return [
                'label' => $month->translatedFormat('F Y'),
                'infaq' => $infaqByMonth[$key] ?? 0,
                'zakat' => $zakatByMonth[$key] ?? 0,
            ];
        })->reverse()->values();

        $qurbanSummary = [
            'total_animals'     => SacrificialAnimal::count(),
            'available'         => SacrificialAnimal::where('status', 'available')->count(),
            'fully_booked'      => SacrificialAnimal::where('status', 'fully_booked')->count(),
            'slaughtered'       => SacrificialAnimal::where('status', 'slaughtered')->count(),
            'successful_orders' => QurbanOrder::where('payment_status', 'success')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'monthlyFunds', 'qurbanSummary'));
    }
}
