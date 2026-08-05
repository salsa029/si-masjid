<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InfaqCampaignRequest;
use App\Models\InfaqCampaign;
use App\Models\InfaqCategory;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InfaqCampaignController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(): View
    {
        $campaigns = InfaqCampaign::with('category')
            ->withSum(['infaqs as collected_amount' => function ($query) {
                $query->where('payment_status', 'success');
            }], 'amount')
            ->latest()
            ->paginate(10);

        return view('admin.infaq-campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $categories = InfaqCategory::orderBy('name')->get();

        return view('admin.infaq-campaigns.create', compact('categories'));
    }

    public function store(InfaqCampaignRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'infaq-campaigns',
                maxWidth: 1200
            );
        }

        InfaqCampaign::create($validated);

        return redirect()->route('admin.infaq-campaigns.index')->with('success', 'Campaign infaq berhasil ditambahkan.');
    }

    public function edit(InfaqCampaign $infaqCampaign): View
    {
        $categories = InfaqCategory::orderBy('name')->get();

        return view('admin.infaq-campaigns.edit', compact('infaqCampaign', 'categories'));
    }

    public function update(InfaqCampaignRequest $request, InfaqCampaign $infaqCampaign): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'infaq-campaigns',
                maxWidth: 1200,
                oldPath: $infaqCampaign->thumbnail,
            );
        }

        $infaqCampaign->update($validated);

        return redirect()->route('admin.infaq-campaigns.index')->with('success', 'Campaign infaq berhasil diperbarui.');
    }

    public function destroy(InfaqCampaign $infaqCampaign): RedirectResponse
    {
        $this->imageUploadService->delete($infaqCampaign->thumbnail);
        $infaqCampaign->delete();

        return redirect()->route('admin.infaq-campaigns.index')->with('success', 'Campaign infaq berhasil dihapus.');
    }
}
