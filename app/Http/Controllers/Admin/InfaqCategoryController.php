<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InfaqCategoryRequest;
use App\Models\InfaqCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InfaqCategoryController extends Controller
{
    public function index(): View
    {
        $infaqCategories = InfaqCategory::withCount('infaqs')->latest()->paginate(10);

        return view('admin.infaq-categories.index', compact('infaqCategories'));
    }

    public function create(): View
    {
        return view('admin.infaq-categories.create');
    }

    public function store(InfaqCategoryRequest $request): RedirectResponse
    {
        InfaqCategory::create($request->validated());

        return redirect()->route('admin.infaq-categories.index')->with('success', 'Kategori infaq berhasil ditambahkan.');
    }

    public function edit(InfaqCategory $infaqCategory): View
    {
        return view('admin.infaq-categories.edit', compact('infaqCategory'));
    }

    public function update(InfaqCategoryRequest $request, InfaqCategory $infaqCategory): RedirectResponse
    {
        $infaqCategory->update($request->validated());

        return redirect()->route('admin.infaq-categories.index')->with('success', 'Kategori infaq berhasil diperbarui.');
    }

    public function destroy(InfaqCategory $infaqCategory): RedirectResponse
    {
        if ($infaqCategory->infaqs()->exists()) {
            return redirect()
                ->route('admin.infaq-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh transaksi infaq.');
        }

        $infaqCategory->delete();

        return redirect()->route('admin.infaq-categories.index')->with('success', 'Kategori infaq berhasil dihapus.');
    }
}
