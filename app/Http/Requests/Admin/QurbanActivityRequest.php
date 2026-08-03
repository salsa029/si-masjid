<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QurbanActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'dkm_chairman_name' => ['nullable', 'string', 'max:255'],
            'dkm_chairman_signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'qurban_chairman_name' => ['nullable', 'string', 'max:255'],
            'qurban_chairman_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kegiatan qurban wajib diisi.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal tidak valid.',
            'dkm_chairman_signature.image' => 'File tanda tangan harus berupa gambar.',
            'dkm_chairman_signature.mimes' => 'Format tanda tangan harus jpg, jpeg, png, atau webp.',
            'dkm_chairman_signature.max' => 'Ukuran gambar tanda tangan maksimal 2MB.',
            'qurban_chairman_photo.image' => 'File foto harus berupa gambar.',
            'qurban_chairman_photo.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'qurban_chairman_photo.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
