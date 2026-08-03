<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationSettingController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function edit(): View
    {
        // Gunakan firstOrNew() agar selalu ada objek settings
        $settings = DonationSetting::firstOrNew();
        return view('admin.donation-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'min_infaq_amount' => ['required', 'numeric', 'min:0'],
            'min_zakat_amount' => ['required', 'numeric', 'min:0'],
            'rice_price_per_kg' => ['required', 'numeric', 'min:0'],
            'gold_price_per_gram' => ['required', 'numeric', 'min:0'],
            'thank_you_message' => ['nullable', 'string'],
            'confirmation_whatsapp' => ['nullable', 'string', 'max:20'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $settings = DonationSetting::firstOrNew();

        if ($request->hasFile('qris_image')) {
            $validated['qris_image'] = $this->imageUploadService->upload(
                $request->file('qris_image'),
                folder: 'donation-settings',
                oldPath: $settings->qris_image,
            );
        }

        $settings->fill($validated)->save();

        return redirect()
            ->route('admin.donation-settings.edit')
            ->with('success', 'Pengaturan modul donasi berhasil diperbarui.');
    }
}
