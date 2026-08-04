<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MosqueGalleryImageRequest;
use App\Models\MosqueGalleryImage;
use App\Models\MosqueProfile;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;

class MosqueGalleryImageController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function store(MosqueGalleryImageRequest $request): RedirectResponse
    {
        $mosqueProfile = MosqueProfile::firstOrCreate([]);

        $nextSortOrder = $mosqueProfile->galleryImages()->max('sort_order') + 1;

        foreach ($request->file('photos') as $photo) {
            $path = $this->imageUploadService->upload(
                $photo,
                folder: 'mosque-profile/gallery',
                maxWidth: 1920
            );

            $mosqueProfile->galleryImages()->create([
                'photo' => $path,
                'caption' => $request->validated('caption'),
                'sort_order' => $nextSortOrder++,
            ]);
        }

        return redirect()
            ->route('admin.mosque-profile.edit')
            ->with('success', 'Foto galeri masjid berhasil ditambahkan.');
    }

    public function destroy(MosqueGalleryImage $galleryImage): RedirectResponse
    {
        $this->imageUploadService->delete($galleryImage->photo);
        $galleryImage->delete();

        return redirect()
            ->route('admin.mosque-profile.edit')
            ->with('success', 'Foto galeri masjid berhasil dihapus.');
    }
}
