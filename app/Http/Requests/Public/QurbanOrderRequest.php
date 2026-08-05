<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'payment_type' => ['required', 'in:full,installment'],
            'installment_count' => ['required_if:payment_type,installment', 'nullable', 'in:2,3'],
        ];
    }

    public function messages(): array
    {
        return [
            'slot_number.required_if' => 'Pilih slot patungan terlebih dahulu.',
            'installment_count.required_if' => 'Pilih jumlah cicilan terlebih dahulu.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('payment_type') !== 'installment') {
                return;
            }

            $activity = $this->route('sacrificialAnimal')?->activity;
            $minDeadline = now()->addDays(2)->startOfDay();

            if (! $activity || ! $activity->end_date || $activity->end_date->lt($minDeadline)) {
                $validator->errors()->add(
                    'payment_type',
                    'Cicilan tidak tersedia untuk hewan ini karena batas waktu pendaftaran kegiatan kurban terlalu dekat atau belum ditentukan.'
                );
            }
        });
    }
}
