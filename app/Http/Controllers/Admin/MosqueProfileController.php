<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MosqueProfileRequest;
use App\Models\MosqueProfile;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MosqueProfileController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function edit(): View
    {
        $mosqueProfile = MosqueProfile::first();
        return view('admin.mosque-profile.edit', compact('mosqueProfile'));
    }

    public function update(MosqueProfileRequest $request): RedirectResponse
    {
        $mosqueProfile = MosqueProfile::firstOrNew();
        $validated = $request->validated();

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $this->imageUploadService->upload(
                $request->file('hero_image'),
                folder: 'mosque-profile',
                maxWidth: 1920,
                oldPath: $mosqueProfile->hero_image
            );
        }

        $mosqueProfile->fill($validated)->save();

        return redirect()
            ->route('admin.mosque-profile.edit')
            ->with('success', 'Profil masjid berhasil diperbarui.');
    }
}
