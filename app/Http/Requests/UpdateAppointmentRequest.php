<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAnyRole(['admin', 'doctor', 'asistente']);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|exists:patients,id',
            'user_id' => 'sometimes|exists:users,id',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => [
                'sometimes',
                'date_format:H:i,H:i:s',
                function ($attribute, $value, $fail) {
                    $appointment = \App\Models\Appointment::findOrFail($this->route('appointment'));

                    $userId = $this->user_id ?? $appointment->user_id;
                    $date = $this->appointment_date ?? $appointment->appointment_date;

                    $exists = \App\Models\Appointment::where('user_id', $userId)
                        ->where('appointment_date', $date)
                        ->where('appointment_time', $value)
                        ->where('id', '!=', $appointment->id)
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
