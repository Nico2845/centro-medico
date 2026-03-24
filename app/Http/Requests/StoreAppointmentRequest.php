<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['admin', 'doctor', 'asistente']);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => [
                'required',
                'date_format:H:i,H:i:s',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Appointment::where('user_id', $this->user_id)
                        ->where('appointment_date', $this->appointment_date)
                        ->where('appointment_time', $value)
                        ->exists();

                    if ($exists) {
                        $fail('El médico ya tiene una cita agendada en ese horario.');
                    }
                },
            ],
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ];
    }
}
