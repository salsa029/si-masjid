<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ZakatTypeRequest;
use App\Models\ZakatType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZakatTypeController extends Controller
{
    public function index(): View
    {
        $zakatTypes = ZakatType::withCount('zakats')->latest()->paginate(10);

        return view('admin.zakat-types.index', compact('zakatTypes'));
    }

    public function create(): View
    {
        return view('admin.zakat-types.create');
    }

    public function store(ZakatTypeRequest $request): RedirectResponse
    {
        ZakatType::create($request->validated());

        return redirect()->route('admin.zakat-types.index')->with('success', 'Jenis zakat berhasil ditambahkan.');
    }

    public function edit(ZakatType $zakatType): View
    {
        return view('admin.zakat-types.edit', compact('zakatType'));
    }

    public function update(ZakatTypeRequest $request, ZakatType $zakatType): RedirectResponse
    {
        $zakatType->update($request->validated());

        return redirect()->route('admin.zakat-types.index')->with('success', 'Jenis zakat berhasil diperbarui.');
    }

    public function destroy(ZakatType $zakatType): RedirectResponse
    {
        if ($zakatType->zakats()->exists()) {
            return redirect()->route('admin.zakat-types.index')->with('error', 'Jenis zakat tidak bisa dihapus karena masih memiliki riwayat transaksi.');
        }

        $zakatType->delete();

        return redirect()->route('admin.zakat-types.index')->with('success', 'Jenis zakat berhasil dihapus.');
    }
}
