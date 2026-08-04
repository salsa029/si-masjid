<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MosqueGalleryImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'Pilih minimal 1 foto untuk diunggah.',
            'photos.*.image' => 'Setiap file yang diunggah harus berupa gambar.',
            'photos.*.max' => 'Ukuran setiap gambar maksimal 2 MB.',
        ];
    }
}
