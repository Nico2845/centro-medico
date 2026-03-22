<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('medicalRecord')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Patient::class);
        
        $patient = Patient::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Paciente creado correctamente',
            'data' => $patient
        ], 201);
    }

    public function show(Patient $patient)
    {
        $patient->load('medicalRecord');

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $patient->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Paciente actualizado correctamente',
            'data' => $patient
        ]);
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Paciente eliminado correctamente'
        ]);
    }
}