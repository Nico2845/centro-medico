<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['admin', 'asistente']);
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:patients,email',
            'phone'      => 'nullable|string|max:20',
            'dui'        => 'nullable|string|unique:patients,dui',
            'birth_date' => 'nullable|date',
            'address'    => 'nullable|string',
        ];
    }
}
