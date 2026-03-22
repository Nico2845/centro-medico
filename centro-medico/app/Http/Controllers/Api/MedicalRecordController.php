<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index($patientId)
    {
        $this->authorize('viewAny', MedicalRecord::class);

        $patient = Patient::findOrFail($patientId);
        return response()->json($patient->medicalRecord);
    }

    public function store(StoreMedicalRecordRequest $request, $patientId)
    {
        $this->authorize('create', MedicalRecord::class);

        $patient = Patient::findOrFail($patientId);
        $medicalRecord = $patient->medicalRecord()->create($request->validated());

        return response()->json($medicalRecord, 201);
    }

    public function show($patientId)
    {
        $this->authorize('view', MedicalRecord::class);

        $patient = Patient::findOrFail($patientId);
        return response()->json($patient->medicalRecord);
    }

    public function update(UpdateMedicalRecordRequest $request, $patientId)
    {
        $this->authorize('update', MedicalRecord::class);

        $patient = Patient::findOrFail($patientId);
        $medicalRecord = $patient->medicalRecord;

        if (!$medicalRecord) {
            return response()->json(['message' => 'Medical record not found'], 404);
        }

        $medicalRecord->update($request->validated());
        return response()->json($medicalRecord);
    }

    public function destroy($patientId)
    {
        $this->authorize('delete', MedicalRecord::class);

        $patient = Patient::findOrFail($patientId);
        $medicalRecord = $patient->medicalRecord;

        if (!$medicalRecord) {
            return response()->json(['message' => 'Medical record not found'], 404);
        }

        $medicalRecord->delete();
        return response()->json(['message' => 'Medical record deleted successfully']);
    }
}