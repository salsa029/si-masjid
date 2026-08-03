<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SlaughterDocumentationRequest;
use App\Models\SacrificialAnimal;
use App\Models\SlaughterDocumentation;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;

class SlaughterDocumentationController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function store(SlaughterDocumentationRequest $request, SacrificialAnimal $sacrificialAnimal): RedirectResponse
    {
        foreach ($request->file('photos') as $photo) {
            $path = $this->imageUploadService->upload(
                $photo,
                folder: 'sacrificial-animals/documentations',
                maxWidth: 1200
            );

            $sacrificialAnimal->documentations()->create([
                'photo' => $path,
                'description' => $request->validated('description'),
            ]);
        }

        return redirect()
            ->route('admin.sacrificial-animals.edit', $sacrificialAnimal)
            ->with('success', 'Dokumentasi penyembelihan berhasil ditambahkan.');
    }

    public function destroy(SacrificialAnimal $sacrificialAnimal, SlaughterDocumentation $documentation): RedirectResponse
    {
        $this->imageUploadService->delete($documentation->photo);
        $documentation->delete();

        return redirect()
            ->route('admin.sacrificial-animals.edit', $sacrificialAnimal)
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
