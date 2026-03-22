<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;

class MedicalRecordController extends Controller
{
    public function index(Patient $patient)
    {
        return response()->json([
            'success' => true,
            'data' => $patient->medicalRecord
        ]);
    }
    public function store(StoreMedicalRecordRequest $request, Patient $patient)
    {
        if ($patient->medicalRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Este paciente ya tiene un expediente médico'
            ], 422);
        }

        $record = $patient->medicalRecord()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Expediente médico creado correctamente',
            'data' => $record
        ], 201);
    }

    public function show(Patient $patient, MedicalRecord $record)
    {
        if ($record->patient_id !== $patient->id) {
            return response()->json([
                'success' => false,
                'message' => 'Expediente no encontrado para este paciente'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    public function update(UpdateMedicalRecordRequest $request, Patient $patient, MedicalRecord $record)
    {
        if ($record->patient_id !== $patient->id) {
            return response()->json([
                'success' => false,
                'message' => 'Expediente no encontrado para este paciente'
            ], 404);
        }

        $record->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Expediente actualizado correctamente',
            'data' => $record
        ]);
    }
}