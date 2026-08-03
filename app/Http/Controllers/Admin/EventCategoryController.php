<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventCategoryRequest;
use App\Models\EventCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventCategoryController extends Controller
{
    public function index(): View
    {
        $eventCategories = EventCategory::withCount('events')->latest()->paginate(10);

        return view('admin.event-categories.index', compact('eventCategories'));
    }

    public function create(): View
    {
        return view('admin.event-categories.create');
    }

    public function store(EventCategoryRequest $request): RedirectResponse
    {
        EventCategory::create($request->validated());

        return redirect()->route('admin.event-categories.index')->with('success', 'Kategori kegiatan berhasil ditambahkan.');
    }

    public function edit(EventCategory $eventCategory): View
    {
        return view('admin.event-categories.edit', compact('eventCategory'));
    }

    public function update(EventCategoryRequest $request, EventCategory $eventCategory): RedirectResponse
    {
        $eventCategory->update($request->validated());

        return redirect()->route('admin.event-categories.index')->with('success', 'Kategori kegiatan berhasil diperbarui.');
    }

    public function destroy(EventCategory $eventCategory): RedirectResponse
    {
        if ($eventCategory->events()->exists()) {
            return redirect()
                ->route('admin.event-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh kegiatan.');
        }

        $eventCategory->delete();

        return redirect()->route('admin.event-categories.index')->with('success', 'Kategori kegiatan berhasil dihapus.');
    }
}
