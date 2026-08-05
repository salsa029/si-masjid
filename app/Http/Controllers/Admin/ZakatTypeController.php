<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ZakatTypeRequest;
use App\Models\ZakatType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZakatTypeController extends AbstractCategoryController
{
    public function index(): View
    {
        return $this->renderIndex(ZakatType::class, 'zakats', 'admin.zakat-types.index', 'zakatTypes');
    }

    public function create(): View
    {
        return $this->renderCreate('admin.zakat-types.create');
    }

    public function store(ZakatTypeRequest $request): RedirectResponse
    {
        return $this->storeAndRedirect(ZakatType::class, $request->validated(), 'admin.zakat-types.index', 'Jenis zakat berhasil ditambahkan.');
    }

    public function edit(ZakatType $zakatType): View
    {
        return $this->renderEdit($zakatType, 'admin.zakat-types.edit', 'zakatType');
    }

    public function update(ZakatTypeRequest $request, ZakatType $zakatType): RedirectResponse
    {
        return $this->updateAndRedirect($zakatType, $request->validated(), 'admin.zakat-types.index', 'Jenis zakat berhasil diperbarui.');
    }

    public function destroy(ZakatType $zakatType): RedirectResponse
    {
        return $this->destroyWithGuard(
            $zakatType,
            'zakats',
            'admin.zakat-types.index',
            'Jenis zakat tidak bisa dihapus karena masih memiliki riwayat transaksi.',
            'Jenis zakat berhasil dihapus.'
        );
    }
}
