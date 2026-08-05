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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'dkm_chairman_name' => ['nullable', 'string', 'max:255'],
            'dkm_chairman_signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'qurban_chairman_name' => ['nullable', 'string', 'max:255'],
            'qurban_chairman_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'certificate_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'certificate_name_top' => ['nullable', 'numeric', 'min:0', 'max:210'],
            'certificate_name_left' => ['nullable', 'numeric', 'min:0', 'max:297'],
            'certificate_name_font_size' => ['nullable', 'integer', 'min:8', 'max:72'],
            'certificate_year_top' => ['nullable', 'numeric', 'min:0', 'max:210'],
            'certificate_year_left' => ['nullable', 'numeric', 'min:0', 'max:297'],
            'certificate_year_font_size' => ['nullable', 'integer', 'min:8', 'max:72'],
            'certificate_animal_top' => ['nullable', 'numeric', 'min:0', 'max:210'],
            'certificate_animal_left' => ['nullable', 'numeric', 'min:0', 'max:297'],
            'certificate_animal_font_size' => ['nullable', 'integer', 'min:8', 'max:72'],
            'certificate_dkm_name_top' => ['nullable', 'numeric', 'min:0', 'max:210'],
            'certificate_dkm_name_left' => ['nullable', 'numeric', 'min:0', 'max:297'],
            'certificate_dkm_name_font_size' => ['nullable', 'integer', 'min:8', 'max:72'],
            'certificate_panitia_name_top' => ['nullable', 'numeric', 'min:0', 'max:210'],
            'certificate_panitia_name_left' => ['nullable', 'numeric', 'min:0', 'max:297'],
            'certificate_panitia_name_font_size' => ['nullable', 'integer', 'min:8', 'max:72'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kegiatan qurban wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.date' => 'Tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'dkm_chairman_signature.image' => 'File tanda tangan harus berupa gambar.',
            'dkm_chairman_signature.mimes' => 'Format tanda tangan harus jpg, jpeg, png, atau webp.',
            'dkm_chairman_signature.max' => 'Ukuran gambar tanda tangan maksimal 2MB.',
            'qurban_chairman_photo.image' => 'File foto harus berupa gambar.',
            'qurban_chairman_photo.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'qurban_chairman_photo.max' => 'Ukuran foto maksimal 2MB.',
            'certificate_background.image' => 'File latar sertifikat harus berupa gambar.',
            'certificate_background.mimes' => 'Format latar sertifikat harus jpg, jpeg, png, atau webp.',
            'certificate_background.max' => 'Ukuran latar sertifikat maksimal 5MB.',
        ];
    }
}
