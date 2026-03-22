<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'doctor']);
    }

    public function rules(): array
    {
        return [
            'blood_type'          => 'sometimes|string|max:10',
            'allergies'           => 'sometimes|string',
            'medical_history'     => 'sometimes|string',
            'current_medications' => 'sometimes|string',
            'notes'               => 'sometimes|string',
        ];
    }
}