<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QurbanOrder;
use App\Models\SacrificialAnimal;
use Illuminate\View\View;

class QurbanDashboardController extends Controller
{
    public function index(): View
    {
        $animals = SacrificialAnimal::withBookingStats()
            ->orderBy('animal_type')
            ->get();

        $summary = [
            'total_animals' => $animals->count(),
            'total_revenue' => QurbanOrder::where('payment_status', 'success')->sum('total_amount'),
            'total_successful_orders' => QurbanOrder::where('payment_status', 'success')->count(),
            'pending_verifications' => QurbanOrder::where('payment_method', 'manual_transfer')
                ->where('payment_status', 'awaiting_verification')
                ->count(),
        ];

        return view('admin.qurban-dashboard.index', compact('animals', 'summary'));
    }
}
