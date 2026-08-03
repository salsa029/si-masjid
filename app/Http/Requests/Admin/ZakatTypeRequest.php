<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZakatTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('zakat_types', 'name')->ignore($this->route('zakat_type'))],
            'description' => ['nullable', 'string'],
            'calculation_unit' => ['required', 'in:fixed_per_soul,nishab_percentage'],
            'nishab_amount' => ['nullable', 'required_if:calculation_unit,nishab_percentage', 'numeric', 'min:0'],
            'nishab_description' => ['nullable', 'string'],
            'rate_percentage' => ['required', 'numeric', 'min:0.1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama jenis zakat wajib diisi.',
            'nishab_amount.required_if' => 'Nilai nishab wajib diisi untuk jenis zakat berbasis persentase.',
        ];
    }
}
