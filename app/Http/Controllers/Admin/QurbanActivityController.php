<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QurbanActivityRequest;
use App\Models\QurbanActivity;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QurbanActivityController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(): View
    {
        $qurbanActivities = QurbanActivity::withCount('animals')
            ->latest('date')
            ->paginate(10);

        return view('admin.qurban-activities.index', compact('qurbanActivities'));
    }

    public function create(): View
    {
        return view('admin.qurban-activities.create');
    }

    public function store(QurbanActivityRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('dkm_chairman_signature')) {
            $validated['dkm_chairman_signature'] = $this->imageUploadService->upload(
                $request->file('dkm_chairman_signature'),
                folder: 'qurban-activities',
                maxWidth: 800
            );
        }

        if ($request->hasFile('qurban_chairman_photo')) {
            $validated['qurban_chairman_photo'] = $this->imageUploadService->upload(
                $request->file('qurban_chairman_photo'),
                folder: 'qurban-activities',
                maxWidth: 800
            );
        }

        QurbanActivity::create($validated);

        return redirect()
            ->route('admin.qurban-activities.index')
            ->with('success', 'Qurban Activity berhasil ditambahkan.');
    }

    public function edit(QurbanActivity $qurbanActivity): View
    {
        return view('admin.qurban-activities.edit', compact('qurbanActivity'));
    }

    public function update(QurbanActivityRequest $request, QurbanActivity $qurbanActivity): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('dkm_chairman_signature')) {
            $validated['dkm_chairman_signature'] = $this->imageUploadService->upload(
                $request->file('dkm_chairman_signature'),
                folder: 'qurban-activities',
                maxWidth: 800,
                oldPath: $qurbanActivity->dkm_chairman_signature,
            );
        }

        if ($request->hasFile('qurban_chairman_photo')) {
            $validated['qurban_chairman_photo'] = $this->imageUploadService->upload(
                $request->file('qurban_chairman_photo'),
                folder: 'qurban-activities',
                maxWidth: 800,
                oldPath: $qurbanActivity->qurban_chairman_photo,
            );
        }

        $qurbanActivity->update($validated);

        return redirect()
            ->route('admin.qurban-activities.index')
            ->with('success', 'Qurban Activity berhasil diperbarui.');
    }

    public function destroy(QurbanActivity $qurbanActivity): RedirectResponse
    {
        if ($qurbanActivity->animals()->exists()) {
            return redirect()
                ->route('admin.qurban-activities.index')
                ->with('error', 'Qurban Activity tidak bisa dihapus karena masih memiliki data Hewan Kurban terkait.');
        }

        $this->imageUploadService->delete($qurbanActivity->dkm_chairman_signature);
        $this->imageUploadService->delete($qurbanActivity->qurban_chairman_photo);
        $qurbanActivity->delete();

        return redirect()
            ->route('admin.qurban-activities.index')
            ->with('success', 'Qurban Activity berhasil dihapus.');
    }
}
