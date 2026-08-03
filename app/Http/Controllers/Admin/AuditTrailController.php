<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class AuditTrailController extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::with('causer')
            ->when($request->filled('module'), fn($query) => $query->where('log_name', $request->input('module')))
            ->when($request->filled('date_from'), fn($query) => $query->whereDate('created_at', '>=', $request->input('date_from')))
            ->when($request->filled('date_to'), fn($query) => $query->whereDate('created_at', '<=', $request->input('date_to')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $availableModules = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('admin.audit-trail.index', compact('activities', 'availableModules'));
    }
}
