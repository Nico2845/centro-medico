<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;

class PatientPolicy
{
    /**
     * Determine whether the user can view any patients.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'assistant', 'doctor']);
    }

    /**
     * Determine whether the user can view the patient.
     */
    public function view(User $user, Patient $patient)
    {
        return in_array($user->role, ['admin', 'assistant', 'doctor']);
    }

    /**
     * Determine whether the user can create patients.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'assistant']);
    }

    /**
     * Determine whether the user can update the patient.
     */
    public function update(User $user, Patient $patient)
    {
        return in_array($user->role, ['admin', 'assistant']);
    }

    /**
     * Determine whether the user can delete the patient.
     */
    public function delete(User $user, Patient $patient)
    {
        return $user->role === 'admin';
    }
}