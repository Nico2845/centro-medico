<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'asistente']);
    }

    public function rules(): array
    {
        
        $patientId = $this->route('patient') ? $this->route('patient')->id : null;

        return [
            'name' => 'required|string|max:255',
            'dui' => 'required|string|max:10|unique:patients,dui,' . $patientId,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:patients,email,' . $patientId,
            'birth_date' => 'nullable|date|before_or_equal:today',
            'address' => 'nullable|string',
        ];
    }
}