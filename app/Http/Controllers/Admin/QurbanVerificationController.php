<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QurbanOrder;
use App\Services\QurbanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class QurbanVerificationController extends Controller
{
    public function __construct(protected QurbanService $qurbanService) {}

    public function index(): View
    {
        $pendingVerifications = QurbanOrder::with(['user', 'animal'])
            ->where('payment_method', 'manual_transfer')
            ->where('payment_status', 'awaiting_verification')
            ->latest()
            ->paginate(10);

        return view('admin.qurban-verifications.index', compact('pendingVerifications'));
    }

    public function show(QurbanOrder $qurbanOrder): View
    {
        abort_unless($qurbanOrder->payment_method === 'manual_transfer', 404);

        $qurbanOrder->load(['user', 'animal']);

        return view('admin.qurban-verifications.show', compact('qurbanOrder'));
    }

    public function approve(QurbanOrder $qurbanOrder): RedirectResponse
    {
        abort_unless($qurbanOrder->payment_status === 'awaiting_verification', 400);

        $this->qurbanService->markOrderAsSuccess($qurbanOrder, 'manual_transfer', Auth::id());

        return redirect()
            ->route('admin.qurban-verifications.index')
            ->with('success', 'Pembayaran berhasil diverifikasi dan dikonfirmasi.');
    }

    public function reject(Request $request, QurbanOrder $qurbanOrder): RedirectResponse
    {
        abort_unless($qurbanOrder->payment_status === 'awaiting_verification', 400);

        $request->validate([
            'verification_note' => ['required', 'string', 'max:500'],
        ], [
            'verification_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $qurbanOrder->update([
            'payment_status' => 'failed',
            'verification_note' => $request->input('verification_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.qurban-verifications.index')
            ->with('success', 'Pembayaran ditolak. Jamaah dapat melihat alasan penolakan pada riwayat pesanannya.');
    }
}
