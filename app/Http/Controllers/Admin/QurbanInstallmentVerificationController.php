<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QurbanInstallment;
use App\Services\QurbanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class QurbanInstallmentVerificationController extends Controller
{
    public function __construct(protected QurbanService $qurbanService) {}

    public function index(): View
    {
        $pendingVerifications = QurbanInstallment::with(['order.user', 'order.animal'])
            ->where('payment_status', 'awaiting_verification')
            ->latest()
            ->paginate(10);

        return view('admin.qurban-installment-verifications.index', compact('pendingVerifications'));
    }

    public function show(QurbanInstallment $installment): View
    {
        abort_unless($installment->payment_status === 'awaiting_verification', 404);

        $installment->load(['order.user', 'order.animal']);

        return view('admin.qurban-installment-verifications.show', compact('installment'));
    }

    public function approve(QurbanInstallment $installment): RedirectResponse
    {
        abort_unless($installment->payment_status === 'awaiting_verification', 400);

        $this->qurbanService->markInstallmentAsSuccess($installment, 'manual_transfer', Auth::id());

        return redirect()
            ->route('admin.qurban-installment-verifications.index')
            ->with('success', 'Cicilan berhasil diverifikasi dan dikonfirmasi.');
    }

    public function reject(Request $request, QurbanInstallment $installment): RedirectResponse
    {
        abort_unless($installment->payment_status === 'awaiting_verification', 400);

        $request->validate([
            'verification_note' => ['required', 'string', 'max:500'],
        ], [
            'verification_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $installment->update([
            'payment_status' => 'failed',
            'verification_note' => $request->input('verification_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.qurban-installment-verifications.index')
            ->with('success', 'Cicilan ditolak. Jamaah dapat mengunggah ulang bukti pembayaran untuk cicilan ini.');
    }
}
