<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can add authorization logic here if needed
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'El ID del paciente es obligatorio.',
            'patient_id.exists' => 'El paciente seleccionado no existe.',
            'blood_type.max' => 'El tipo de sangre no puede exceder los 10 caracteres.',
        ];
    }
}