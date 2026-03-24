<?php

use Illuminate\Support\Facades\Route;

// Aquí puedes definir las rutas web de tu aplicación

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el módulo de pacientes (solo accesibles para el rol assistant)
Route::middleware(['auth:sanctum', 'can:viewAny,App\Models\Patient'])->group(function () {
    Route::get('/patients', [App\Http\Controllers\Api\PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [App\Http\Controllers\Api\PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [App\Http\Controllers\Api\PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [App\Http\Controllers\Api\PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [App\Http\Controllers\Api\PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [App\Http\Controllers\Api\PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [App\Http\Controllers\Api\PatientController::class, 'destroy'])->name('patients.destroy');
});