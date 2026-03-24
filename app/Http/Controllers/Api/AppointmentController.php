<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Appointment::with('patient', 'doctor')->get()
        );
    }

    public function store(StoreAppointmentRequest $request)
    {
        $exists = Appointment::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'El médico ya tiene una cita agendada en ese horario.'
            ], 422);
        }

        $appointment = Appointment::create($request->validated());

        return response()->json($appointment->load('patient', 'doctor'), 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with('patient', 'doctor')->findOrFail($id);

        return response()->json($appointment);
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $userId = $request->user_id ?? $appointment->user_id;
        $date = $request->appointment_date ?? $appointment->appointment_date;
        $time = $request->appointment_time ?? $appointment->appointment_time;

        $exists = Appointment::where('user_id', $userId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'El médico ya tiene una cita agendada en ese horario.'
            ], 422);
        }

        $appointment->update($request->validated());

        return response()->json($appointment->load('patient', 'doctor'));
    }
    
    public function byDoctor($id)
    {
        $appointments = Appointment::with('patient', 'doctor')
            ->where('user_id', $id)
            ->get();

        return response()->json($appointments);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Cita eliminada']);
    }
}
