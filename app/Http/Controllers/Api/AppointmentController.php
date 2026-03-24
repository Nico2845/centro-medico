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
        $appointment->update($request->validated());

        return response()->json($appointment->load('patient', 'doctor'));
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Cita eliminada']);
    }
}
