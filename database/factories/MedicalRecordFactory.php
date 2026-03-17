<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'allergies' => fake()->randomElement(['Ninguna', 'Penicilina', 'Polen', 'Mariscos', 'Látex']),
            'medical_history' => fake()->paragraph(),
            'current_medications' => fake()->randomElement(['Ninguno', 'Metformina', 'Losartán', 'Omeprazol']),
            'notes' => fake()->sentence(),
        ];
    }
}