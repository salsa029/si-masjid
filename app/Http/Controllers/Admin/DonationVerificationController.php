<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\Zakat;
use App\Services\DonationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonationVerificationController extends Controller
{
    public function __construct(protected DonationService $donationService) {}

    // --- SEKSI INFAQ ---

    public function infaqIndex(): View
    {
        return $this->renderIndex(Infaq::class, ['user', 'category', 'campaign'], 'admin.donation-verifications.infaq-index');
    }

    public function infaqShow(Infaq $infaq): View
    {
        return $this->renderShow($infaq, ['user', 'category', 'campaign'], 'admin.donation-verifications.infaq-show', 'infaq');
    }

    public function infaqApprove(Infaq $infaq): RedirectResponse
    {
        return $this->approve($infaq, 'infaq', 'admin.donation-verifications.infaq.index', 'Infaq berhasil diverifikasi.');
    }

    public function infaqReject(Request $request, Infaq $infaq): RedirectResponse
    {
        return $this->reject($request, $infaq, 'infaq', 'admin.donation-verifications.infaq.index', 'Infaq ditolak.');
    }

    // --- SEKSI ZAKAT ---

    public function zakatIndex(): View
    {
        return $this->renderIndex(Zakat::class, ['user', 'zakatType'], 'admin.donation-verifications.zakat-index');
    }

    public function zakatShow(Zakat $zakat): View
    {
        return $this->renderShow($zakat, ['user', 'zakatType'], 'admin.donation-verifications.zakat-show', 'zakat');
    }

    public function zakatApprove(Zakat $zakat): RedirectResponse
    {
        return $this->approve($zakat, 'zakat', 'admin.donation-verifications.zakat.index', 'Zakat berhasil diverifikasi.');
    }

    public function zakatReject(Request $request, Zakat $zakat): RedirectResponse
    {
        return $this->reject($request, $zakat, 'zakat', 'admin.donation-verifications.zakat.index', 'Zakat ditolak.');
    }

    // --- LOGIKA BERSAMA ---

    private function renderIndex(string $modelClass, array $with, string $view): View
    {
        $items = $modelClass::with($with)
            ->where('payment_method', 'manual_transfer')
            ->where('payment_status', 'awaiting_verification')
            ->latest()
            ->paginate(10);

        return view($view, compact('items'));
    }

    private function renderShow(Model $transaction, array $with, string $view, string $variable): View
    {
        abort_unless($transaction->payment_method === 'manual_transfer', 404);
        abort_unless($transaction->payment_status === 'awaiting_verification', 404);

        $transaction->load($with);

        return view($view, [$variable => $transaction]);
    }

    private function approve(Model $transaction, string $logName, string $routeIndex, string $successMessage): RedirectResponse
    {
        abort_unless($transaction->payment_method === 'manual_transfer', 400);
        abort_unless($transaction->payment_status === 'awaiting_verification', 400);

        $this->donationService->markAsSuccess($transaction, 'manual_transfer', Auth::id());

        activity($logName)
            ->causedBy(Auth::user())
            ->performedOn($transaction)
            ->withProperties(['ip_address' => request()->ip()])
            ->log("Memverifikasi (menyetujui) pembayaran {$logName} #{$transaction->id}");

        return redirect()->route($routeIndex)->with('success', $successMessage);
    }

    private function reject(Request $request, Model $transaction, string $logName, string $routeIndex, string $successMessage): RedirectResponse
    {
        abort_unless($transaction->payment_method === 'manual_transfer', 400);
        abort_unless($transaction->payment_status === 'awaiting_verification', 400);

        $request->validate([
            'verification_note' => ['required', 'string', 'max:500'],
        ], [
            'verification_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $transaction->update([
            'payment_status' => 'failed',
            'verification_note' => $request->input('verification_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        activity($logName)
            ->causedBy(Auth::user())
            ->performedOn($transaction)
            ->withProperties([
                'ip_address' => request()->ip(),
                'reason' => $request->input('verification_note'),
            ])
            ->log("Menolak verifikasi pembayaran {$logName} #{$transaction->id} dengan alasan: " . $request->input('verification_note'));

        return redirect()->route($routeIndex)->with('success', $successMessage);
    }
}
