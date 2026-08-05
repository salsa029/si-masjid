<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SacrificialAnimalRequest;
use App\Models\QurbanActivity;
use App\Models\SacrificialAnimal;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SacrificialAnimalController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request): View
    {
        $qurbanActivities = QurbanActivity::latest('start_date')->get();

        // Wajib pilih Qurban Activity dulu; default ke kegiatan terbaru
        // supaya daftar hewan tidak tercampur antar tahun/kegiatan.
        $selectedActivityId = $request->input('qurban_activity_id', optional($qurbanActivities->first())->id);

        $animals = SacrificialAnimal::query()
            ->when($selectedActivityId, fn($q) => $q->where('qurban_activity_id', $selectedActivityId))
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('animal_type'), fn($q) => $q->where('animal_type', $request->input('animal_type')))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.sacrificial-animals.index', compact('animals', 'qurbanActivities', 'selectedActivityId'));
    }

    public function create(): View
    {
        $qurbanActivities = QurbanActivity::latest('start_date')->get();

        return view('admin.sacrificial-animals.create', compact('qurbanActivities'));
    }

    public function store(SacrificialAnimalRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageUploadService->upload(
                $request->file('photo'),
                folder: 'sacrificial-animals',
                maxWidth: 1200
            );
        }

        $sacrificialAnimal = SacrificialAnimal::create($validated);

        return redirect()
            ->route('admin.sacrificial-animals.edit', $sacrificialAnimal)
            ->with('success', 'Data hewan kurban berhasil ditambahkan.');
    }

    public function edit(SacrificialAnimal $sacrificialAnimal): View
    {
        $sacrificialAnimal->load('documentations');
        $qurbanActivities = QurbanActivity::latest('start_date')->get();

        return view('admin.sacrificial-animals.edit', compact('sacrificialAnimal', 'qurbanActivities'));
    }

    public function update(SacrificialAnimalRequest $request, SacrificialAnimal $sacrificialAnimal): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageUploadService->upload(
                $request->file('photo'),
                folder: 'sacrificial-animals',
                maxWidth: 1200,
                oldPath: $sacrificialAnimal->photo,
            );
        }

        $sacrificialAnimal->update($validated);

        return redirect()
            ->route('admin.sacrificial-animals.index')
            ->with('success', 'Data hewan kurban berhasil diperbarui.');
    }

    public function destroy(SacrificialAnimal $sacrificialAnimal): RedirectResponse
    {
        foreach ($sacrificialAnimal->documentations as $documentation) {
            $this->imageUploadService->delete($documentation->photo);
        }

        $this->imageUploadService->delete($sacrificialAnimal->photo);
        $sacrificialAnimal->delete();

        return redirect()
            ->route('admin.sacrificial-animals.index')
            ->with('success', 'Data hewan kurban berhasil dihapus.');
    }
}
