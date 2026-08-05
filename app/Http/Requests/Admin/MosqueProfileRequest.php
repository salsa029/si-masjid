<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MosqueProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani middleware role:admin di route
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'history' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'contact' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama masjid wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'hero_image.image' => 'File yang diunggah harus berupa gambar.',
            'hero_image.max' => 'Ukuran gambar maksimal 2 MB.',
            'facebook_url.url' => 'Link Facebook harus berupa URL yang valid (contoh: https://facebook.com/namamasjid).',
            'instagram_url.url' => 'Link Instagram harus berupa URL yang valid (contoh: https://instagram.com/namamasjid).',
            'youtube_url.url' => 'Link YouTube harus berupa URL yang valid.',
            'whatsapp_url.url' => 'Link WhatsApp harus berupa URL yang valid (contoh: https://wa.me/6281234567890).',
        ];
    }
}
