<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Kerangka bersama untuk controller kategori/jenis sederhana (index/create/store/edit/update/destroy)
 * yang polanya identik: ArticleCategory, EventCategory, InfaqCategory, ZakatType.
 */
abstract class AbstractCategoryController extends Controller
{
    protected function renderIndex(string $modelClass, string $countRelation, string $view, string $variable): View
    {
        $items = $modelClass::withCount($countRelation)->latest()->paginate(10);

        return view($view, [$variable => $items]);
    }

    protected function renderCreate(string $view): View
    {
        return view($view);
    }

    protected function storeAndRedirect(string $modelClass, array $validated, string $routeIndex, string $successMessage): RedirectResponse
    {
        $modelClass::create($validated);

        return redirect()->route($routeIndex)->with('success', $successMessage);
    }

    protected function renderEdit(Model $model, string $view, string $variable): View
    {
        return view($view, [$variable => $model]);
    }

    protected function updateAndRedirect(Model $model, array $validated, string $routeIndex, string $successMessage): RedirectResponse
    {
        $model->update($validated);

        return redirect()->route($routeIndex)->with('success', $successMessage);
    }

    protected function destroyWithGuard(Model $model, string $usageRelation, string $routeIndex, string $blockedMessage, string $successMessage): RedirectResponse
    {
        if ($model->{$usageRelation}()->exists()) {
            return redirect()->route($routeIndex)->with('error', $blockedMessage);
        }

        $model->delete();

        return redirect()->route($routeIndex)->with('success', $successMessage);
    }
}
