<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleCategoryRequest;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleCategoryController extends AbstractCategoryController
{
    public function index(): View
    {
        return $this->renderIndex(ArticleCategory::class, 'articles', 'admin.article-categories.index', 'articleCategories');
    }

    public function create(): View
    {
        return $this->renderCreate('admin.article-categories.create');
    }

    public function store(ArticleCategoryRequest $request): RedirectResponse
    {
        return $this->storeAndRedirect(ArticleCategory::class, $request->validated(), 'admin.article-categories.index', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ArticleCategory $articleCategory): View
    {
        return $this->renderEdit($articleCategory, 'admin.article-categories.edit', 'articleCategory');
    }

    public function update(ArticleCategoryRequest $request, ArticleCategory $articleCategory): RedirectResponse
    {
        return $this->updateAndRedirect($articleCategory, $request->validated(), 'admin.article-categories.index', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ArticleCategory $articleCategory): RedirectResponse
    {
        return $this->destroyWithGuard(
            $articleCategory,
            'articles',
            'admin.article-categories.index',
            'Kategori tidak bisa dihapus karena masih dipakai oleh artikel.',
            'Kategori berhasil dihapus.'
        );
    }
}
