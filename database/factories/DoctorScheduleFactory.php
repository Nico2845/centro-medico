<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'day_of_week' => fake()->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            'start_time' => fake()->randomElement(['08:00', '09:00', '10:00', '14:00', '15:00']),
            'end_time' => fake()->randomElement(['11:00', '12:00', '13:00', '17:00', '18:00']),
        ];
    }
}