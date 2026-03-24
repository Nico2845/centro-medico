<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        // Allow only authorized users to update a patient
        return $this->user()->can('update', $this->route('patient'));
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dui' => 'required|string|unique:patients,dui,' . $this->route('patient')->id,
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
        ];
    }
}