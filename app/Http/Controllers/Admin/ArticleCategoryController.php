<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleCategoryRequest;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    public function index(): View
    {
        $articleCategories = ArticleCategory::withCount('articles')->latest()->paginate(10);

        return view('admin.article-categories.index', compact('articleCategories'));
    }

    public function create(): View
    {
        return view('admin.article-categories.create');
    }

    public function store(ArticleCategoryRequest $request): RedirectResponse
    {
        ArticleCategory::create($request->validated());

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ArticleCategory $articleCategory): View
    {
        return view('admin.article-categories.edit', compact('articleCategory'));
    }

    public function update(ArticleCategoryRequest $request, ArticleCategory $articleCategory): RedirectResponse
    {
        $articleCategory->update($request->validated());

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ArticleCategory $articleCategory): RedirectResponse
    {
        if ($articleCategory->articles()->exists()) {
            return redirect()
                ->route('admin.article-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh artikel.');
        }

        $articleCategory->delete();

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
