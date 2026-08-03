<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InfaqCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('infaq_categories', 'name')->ignore($this->route('infaq_category')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori infaq wajib diisi.',
            'name.unique' => 'Nama kategori infaq ini sudah digunakan.',
        ];
    }
}
