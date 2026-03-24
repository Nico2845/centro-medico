<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);
        
        $patients = Patient::paginate(10);
        return response()->json($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Patient::class);
        
        $patient = Patient::create($request->validated());
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('view', $patient);
        
        return response()->json($patient);
    }

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('update', $patient);
        
        $patient->update($request->validated());
        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('delete', $patient);
        
        $patient->delete();
        return response()->json(null, 204);
    }

    public function getMedicalRecords($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('view', $patient);
        
        return response()->json($patient->medicalRecord);
    }

    public function storeMedicalRecord(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('update', $patient);
        
        $patient->medicalRecord()->updateOrCreate(
            ['patient_id' => $patient->id],
            $request->all()
        );

        return response()->json($patient->medicalRecord);
    }
}