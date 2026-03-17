<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'appointment_time' => fake()->randomElement(['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => fake()->sentence(),
        ];
    }
}