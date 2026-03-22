Here is the content for the file `StorePatientRequest.php`:

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dui' => 'required|string|unique:patients,dui|max:10',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'required|date',
            'address' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'dui.required' => 'El DUI es obligatorio.',
            'dui.unique' => 'El DUI ya está en uso.',
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
        ];
    }
}