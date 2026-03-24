<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['admin', 'asistente']);
    }

    public function rules(): array
    {
        return [
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:patients,email,' . $this->route('patient'),
            'phone'      => 'sometimes|string|max:20',
            'dui'        => 'sometimes|string|unique:patients,dui,' . $this->route('patient'),
            'birth_date' => 'sometimes|date',
            'address'    => 'sometimes|string',
        ];
    }
}
