<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\InfaqCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InfaqController extends Controller
{
    public function index(Request $request): View
    {
        $sort = in_array($request->input('sort'), ['amount', 'created_at', 'payment_status']) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $infaqs = Infaq::with(['user', 'category', 'campaign'])
            ->when($request->filled('search'), fn($q) => $q->where('transaction_number', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('category'), fn($q) => $q->where('category_id', $request->input('category')))
            ->when($request->filled('status'), fn($q) => $q->where('payment_status', $request->input('status')))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->input('date_from')))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->input('date_to')))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        $categories = InfaqCategory::orderBy('name')->get();

        return view('admin.infaqs.index', compact('infaqs', 'categories'));
    }
}
