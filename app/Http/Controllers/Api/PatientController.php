<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;

class PatientController extends Controller
{
    public function index()
    {
        return response()->json(Patient::with('medicalRecord')->get());
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::with('medicalRecord', 'appointments.doctor')->findOrFail($id);

        return response()->json($patient);
    }

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->validated());

        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Paciente eliminado']);
    }
}
