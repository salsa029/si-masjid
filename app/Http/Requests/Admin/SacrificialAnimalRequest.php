<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SacrificialAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal_type' => ['required', 'in:sapi,kambing,domba'],
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'numeric', 'min:1'],
            'age' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_participants' => ['required', 'integer', 'min:1', 'max:7'],
            'status' => ['required', 'in:available,fully_booked,slaughtered'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // ✅ Tambahkan field baru
            'package_name' => ['nullable', 'string', 'max:255'],
            'package_description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'animal_type.required' => 'Jenis hewan wajib dipilih.',
            'animal_type.in' => 'Jenis hewan harus sapi, kambing, atau domba.',
            'name.required' => 'Nama/kode hewan wajib diisi.',
            'name.max' => 'Nama hewan maksimal 255 karakter.',
            'weight.required' => 'Bobot hewan wajib diisi.',
            'weight.numeric' => 'Bobot harus berupa angka.',
            'weight.min' => 'Bobot hewan minimal 1 kg.',
            'age.required' => 'Usia hewan wajib diisi.',
            'age.integer' => 'Usia harus berupa angka.',
            'age.min' => 'Usia hewan minimal 1 tahun.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'max_participants.required' => 'Maksimal peserta wajib diisi.',
            'max_participants.integer' => 'Maksimal peserta harus berupa angka.',
            'max_participants.min' => 'Minimal peserta adalah 1 orang.',
            'max_participants.max' => 'Maksimal peserta patungan adalah 7 orang (khusus sapi).',
            'status.required' => 'Status hewan wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',

            // ✅ Tambahkan pesan error untuk field baru
            'package_name.max' => 'Nama paket maksimal 255 karakter.',
            'package_description.string' => 'Deskripsi paket harus berupa teks.',
        ];
    }
}
