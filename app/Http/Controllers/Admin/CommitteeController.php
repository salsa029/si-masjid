<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommitteeRequest;
use App\Models\Committee;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request): View
    {
        $committees = Committee::query()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->input('search') . '%'))
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.committees.index', compact('committees'));
    }

    /**
     * Menampilkan form untuk membuat data pengurus baru.
     */
    public function create(): View
    {
        // Kirim model kosong agar _form.blade.php tidak error
        $committee = new Committee();
        return view('admin.committees.create', compact('committee'));
    }

    public function store(CommitteeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageUploadService->upload(
                $request->file('photo'),
                folder: 'committees',
                maxWidth: 800
            );
        }

        $validated['sort_order'] = ((int) Committee::max('sort_order')) + 1;

        Committee::create($validated);

        return redirect()
            ->route('admin.committees.index')
            ->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function edit(Committee $committee): View
    {
        return view('admin.committees.edit', compact('committee'));
    }

    public function update(CommitteeRequest $request, Committee $committee): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageUploadService->upload(
                $request->file('photo'),
                folder: 'committees',
                maxWidth: 800,
                oldPath: $committee->photo,
            );
        }

        $committee->update($validated);

        return redirect()
            ->route('admin.committees.index')
            ->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Committee $committee): RedirectResponse
    {
        $this->imageUploadService->delete($committee->photo);
        $committee->delete();

        return redirect()
            ->route('admin.committees.index')
            ->with('success', 'Data pengurus berhasil dihapus.');
    }

    /**
     * Tukar urutan tampil dengan pengurus di atasnya.
     */
    public function moveUp(Committee $committee): RedirectResponse
    {
        $previous = Committee::where('sort_order', '<', $committee->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $this->swapSortOrder($committee, $previous);
        }

        return back();
    }

    /**
     * Tukar urutan tampil dengan pengurus di bawahnya.
     */
    public function moveDown(Committee $committee): RedirectResponse
    {
        $next = Committee::where('sort_order', '>', $committee->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $this->swapSortOrder($committee, $next);
        }

        return back();
    }

    protected function swapSortOrder(Committee $a, Committee $b): void
    {
        [$orderA, $orderB] = [$a->sort_order, $b->sort_order];

        $a->update(['sort_order' => $orderB]);
        $b->update(['sort_order' => $orderA]);
    }
}
