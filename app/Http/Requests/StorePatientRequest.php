<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
    return $this->user()->hasRole(['admin', 'asistente']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'dui' => 'required|string|max:10|unique:patients,dui',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:patients,email',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'address' => 'nullable|string',
        ];
    }
}