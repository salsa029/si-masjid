<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InfaqCategoryRequest;
use App\Models\InfaqCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InfaqCategoryController extends AbstractCategoryController
{
    public function index(): View
    {
        return $this->renderIndex(InfaqCategory::class, 'infaqs', 'admin.infaq-categories.index', 'infaqCategories');
    }

    public function create(): View
    {
        return $this->renderCreate('admin.infaq-categories.create');
    }

    public function store(InfaqCategoryRequest $request): RedirectResponse
    {
        return $this->storeAndRedirect(InfaqCategory::class, $request->validated(), 'admin.infaq-categories.index', 'Kategori infaq berhasil ditambahkan.');
    }

    public function edit(InfaqCategory $infaqCategory): View
    {
        return $this->renderEdit($infaqCategory, 'admin.infaq-categories.edit', 'infaqCategory');
    }

    public function update(InfaqCategoryRequest $request, InfaqCategory $infaqCategory): RedirectResponse
    {
        return $this->updateAndRedirect($infaqCategory, $request->validated(), 'admin.infaq-categories.index', 'Kategori infaq berhasil diperbarui.');
    }

    public function destroy(InfaqCategory $infaqCategory): RedirectResponse
    {
        return $this->destroyWithGuard(
            $infaqCategory,
            'infaqs',
            'admin.infaq-categories.index',
            'Kategori tidak bisa dihapus karena masih dipakai oleh transaksi infaq.',
            'Kategori infaq berhasil dihapus.'
        );
    }
}
