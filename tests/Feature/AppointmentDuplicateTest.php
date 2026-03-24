<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'doctor']);
    Role::firstOrCreate(['name' => 'asistente']);
});

it('returns 422 when creating a duplicate appointment for the same doctor at the same time', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = \App\Models\Patient::factory()->create();

    Appointment::create([
        'patient_id'       => $patient->id,
        'user_id'          => $doctor->id,
        'appointment_date' => '2026-04-01',
        'appointment_time' => '09:00',
        'status'           => 'pending',
    ]);

    $response = $this->actingAs($admin, 'sanctum')->postJson('/api/appointments', [
        'patient_id'       => $patient->id,
        'user_id'          => $doctor->id,
        'appointment_date' => '2026-04-01',
        'appointment_time' => '09:00',
        'status'           => 'pending',
    ]);

    $response->assertStatus(422);
});

it('returns 422 when updating an appointment to a slot already taken by the same doctor', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $doctor = User::factory()->create();
    $doctor->assignRole('doctor');

    $patient = \App\Models\Patient::factory()->create();

    Appointment::create([
        'patient_id'       => $patient->id,
        'user_id'          => $doctor->id,
        'appointment_date' => '2026-04-01',
        'appointment_time' => '10:00',
        'status'           => 'pending',
    ]);

    $appointmentToUpdate = Appointment::create([
        'patient_id'       => $patient->id,
        'user_id'          => $doctor->id,
        'appointment_date' => '2026-04-01',
        'appointment_time' => '11:00',
        'status'           => 'pending',
    ]);

    $response = $this->actingAs($admin, 'sanctum')->putJson("/api/appointments/{$appointmentToUpdate->id}", [
        'appointment_time' => '10:00',
    ]);

    $response->assertStatus(422);
});