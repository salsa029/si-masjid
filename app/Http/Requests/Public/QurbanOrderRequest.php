<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class QurbanOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxParticipants = $this->route('sacrificialAnimal')?->max_participants ?? 7;

        return [
            'order_type' => ['required', 'in:full,patungan'],
            'slot_number' => ['required_if:order_type,patungan', 'nullable', 'integer', 'min:1', "max:{$maxParticipants}"],
            'payment_method' => ['required', 'in:midtrans,manual_transfer'],
        ];
    }
    public function messages(): array
    {
        return [
            'slot_number.required_if' => 'Pilih slot patungan terlebih dahulu.',
        ];
    }
}
