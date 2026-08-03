<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\Zakat;
use App\Services\DonationService;
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
        $items = Infaq::with(['user', 'category', 'campaign'])
            ->where('payment_method', 'manual_transfer')
            ->where('payment_status', 'awaiting_verification')
            ->latest()
            ->paginate(10);

        return view('admin.donation-verifications.infaq-index', compact('items'));
    }

    public function infaqShow(Infaq $infaq): View
    {
        abort_unless($infaq->payment_method === 'manual_transfer', 404);
        abort_unless($infaq->payment_status === 'awaiting_verification', 404);

        $infaq->load(['user', 'category', 'campaign']);

        return view('admin.donation-verifications.infaq-show', compact('infaq'));
    }

    public function infaqApprove(Infaq $infaq): RedirectResponse
    {
        abort_unless($infaq->payment_method === 'manual_transfer', 400);
        abort_unless($infaq->payment_status === 'awaiting_verification', 400);

        $this->donationService->markAsSuccess($infaq, 'manual_transfer', Auth::id());

        activity('infaq')
            ->causedBy(Auth::user())
            ->performedOn($infaq)
            ->withProperties(['ip_address' => request()->ip()])
            ->log('Memverifikasi (menyetujui) pembayaran infaq #' . $infaq->id);

        return redirect()
            ->route('admin.donation-verifications.infaq.index')
            ->with('success', 'Infaq berhasil diverifikasi.');
    }

    public function infaqReject(Request $request, Infaq $infaq): RedirectResponse
    {
        abort_unless($infaq->payment_method === 'manual_transfer', 400);
        abort_unless($infaq->payment_status === 'awaiting_verification', 400);

        $request->validate([
            'verification_note' => ['required', 'string', 'max:500'],
        ], [
            'verification_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $infaq->update([
            'payment_status' => 'failed',
            'verification_note' => $request->input('verification_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        activity('infaq')
            ->causedBy(Auth::user())
            ->performedOn($infaq)
            ->withProperties([
                'ip_address' => request()->ip(),
                'reason' => $request->input('verification_note'),
            ])
            ->log('Menolak verifikasi pembayaran infaq #' . $infaq->id . ' dengan alasan: ' . $request->input('verification_note'));

        return redirect()
            ->route('admin.donation-verifications.infaq.index')
            ->with('success', 'Infaq ditolak.');
    }

    // --- SEKSI ZAKAT ---

    public function zakatIndex(): View
    {
        $items = Zakat::with(['user', 'zakatType'])
            ->where('payment_method', 'manual_transfer')
            ->where('payment_status', 'awaiting_verification')
            ->latest()
            ->paginate(10);

        return view('admin.donation-verifications.zakat-index', compact('items'));
    }

    public function zakatShow(Zakat $zakat): View
    {
        abort_unless($zakat->payment_method === 'manual_transfer', 404);
        abort_unless($zakat->payment_status === 'awaiting_verification', 404);

        $zakat->load(['user', 'zakatType']);

        return view('admin.donation-verifications.zakat-show', compact('zakat'));
    }

    public function zakatApprove(Zakat $zakat): RedirectResponse
    {
        abort_unless($zakat->payment_method === 'manual_transfer', 400);
        abort_unless($zakat->payment_status === 'awaiting_verification', 400);

        $this->donationService->markAsSuccess($zakat, 'manual_transfer', Auth::id());

        activity('zakat')
            ->causedBy(Auth::user())
            ->performedOn($zakat)
            ->withProperties(['ip_address' => request()->ip()])
            ->log('Memverifikasi (menyetujui) pembayaran zakat #' . $zakat->id);

        return redirect()
            ->route('admin.donation-verifications.zakat.index')
            ->with('success', 'Zakat berhasil diverifikasi.');
    }

    public function zakatReject(Request $request, Zakat $zakat): RedirectResponse
    {
        abort_unless($zakat->payment_method === 'manual_transfer', 400);
        abort_unless($zakat->payment_status === 'awaiting_verification', 400);

        $request->validate([
            'verification_note' => ['required', 'string', 'max:500'],
        ], [
            'verification_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $zakat->update([
            'payment_status' => 'failed',
            'verification_note' => $request->input('verification_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        activity('zakat')
            ->causedBy(Auth::user())
            ->performedOn($zakat)
            ->withProperties([
                'ip_address' => request()->ip(),
                'reason' => $request->input('verification_note'),
            ])
            ->log('Menolak verifikasi pembayaran zakat #' . $zakat->id . ' dengan alasan: ' . $request->input('verification_note'));

        return redirect()
            ->route('admin.donation-verifications.zakat.index')
            ->with('success', 'Zakat ditolak.');
    }
}
