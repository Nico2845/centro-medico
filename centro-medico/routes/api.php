<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MedicalRecordController;

Route::middleware('auth:sanctum')->group(function () {
    // Patients
    Route::get('/patients', [PatientController::class, 'index']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients/{id}', [PatientController::class, 'show']);
    Route::put('/patients/{id}', [PatientController::class, 'update']);
    Route::delete('/patients/{id}', [PatientController::class, 'destroy']);
    
    // Medical Records
    Route::get('/patients/{id}/records', [MedicalRecordController::class, 'show']);
    Route::post('/patients/{id}/records', [MedicalRecordController::class, 'store']);
});