<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function index()
    {
        return response()->json(MedicalRecord::with('patient')->get());
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        $record = MedicalRecord::create($request->validated());

        return response()->json($record->load('patient'), 201);
    }

    public function show($id)
    {
        $record = MedicalRecord::with('patient')->findOrFail($id);

        return response()->json($record);
    }

    public function update(UpdateMedicalRecordRequest $request, $id)
    {
        $record = MedicalRecord::findOrFail($id);
        $record->update($request->validated());

        return response()->json($record->load('patient'));
    }

    public function destroy($id)
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Expediente eliminado']);
    }
}
