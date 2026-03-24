<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // VER LISTA: Admin, Asistente, Doctor
        return $user->hasRole(['admin', 'asistente', 'doctor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        // VER DETALLE: Admin, Asistente, Doctor
        return $user->hasRole(['admin', 'asistente', 'doctor']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // CREAR: Solo Admin y Asistente (Doctor NO crea expedientes, solo edita)
        return $user->hasRole(['admin', 'asistente']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        // EDITAR: Solo Admin y Doctor (Asistente crea pero NO edita lo clínico)
        return $user->hasRole(['admin', 'doctor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        // ELIMINAR: Nadie (Según tu matriz)
        return false; 
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->hasRole('admin');
    }
}