<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EventCategoryRequest;
use App\Models\EventCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventCategoryController extends AbstractCategoryController
{
    public function index(): View
    {
        return $this->renderIndex(EventCategory::class, 'events', 'admin.event-categories.index', 'eventCategories');
    }

    public function create(): View
    {
        return $this->renderCreate('admin.event-categories.create');
    }

    public function store(EventCategoryRequest $request): RedirectResponse
    {
        return $this->storeAndRedirect(EventCategory::class, $request->validated(), 'admin.event-categories.index', 'Kategori kegiatan berhasil ditambahkan.');
    }

    public function edit(EventCategory $eventCategory): View
    {
        return $this->renderEdit($eventCategory, 'admin.event-categories.edit', 'eventCategory');
    }

    public function update(EventCategoryRequest $request, EventCategory $eventCategory): RedirectResponse
    {
        return $this->updateAndRedirect($eventCategory, $request->validated(), 'admin.event-categories.index', 'Kategori kegiatan berhasil diperbarui.');
    }

    public function destroy(EventCategory $eventCategory): RedirectResponse
    {
        return $this->destroyWithGuard(
            $eventCategory,
            'events',
            'admin.event-categories.index',
            'Kategori tidak bisa dihapus karena masih dipakai oleh kegiatan.',
            'Kategori kegiatan berhasil dihapus.'
        );
    }
}
