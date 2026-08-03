<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zakat;
use App\Models\ZakatType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZakatController extends Controller
{
    public function index(Request $request): View
    {
        $zakats = Zakat::with(['user', 'zakatType'])
            ->when($request->filled('search'), fn($q) => $q->where('transaction_number', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('zakat_type_id'), fn($q) => $q->where('zakat_type_id', $request->input('zakat_type_id')))
            ->when($request->filled('status'), fn($q) => $q->where('payment_status', $request->input('status')))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->input('date_from')))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->input('date_to')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $zakatTypes = ZakatType::orderBy('name')->get();

        return view('admin.zakats.index', compact('zakats', 'zakatTypes'));
    }
}
