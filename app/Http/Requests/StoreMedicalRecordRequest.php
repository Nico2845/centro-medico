<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['admin', 'doctor']);
    }

    public function rules(): array
    {
        return [
            'patient_id'          => 'required|exists:patients,id',
            'blood_type'          => 'nullable|string',
            'allergies'           => 'nullable|string',
            'medical_history'     => 'nullable|string',
            'current_medications' => 'nullable|string',
            'notes'               => 'nullable|string',
        ];
    }
}
