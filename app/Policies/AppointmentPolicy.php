<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'asistente', 'doctor']);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole('doctor')) {
            return $appointment->user_id === $user->id;
        }
        return $user->hasAnyRole(['admin', 'asistente']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'asistente']);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->hasAnyRole(['admin', 'asistente']);
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }
}
