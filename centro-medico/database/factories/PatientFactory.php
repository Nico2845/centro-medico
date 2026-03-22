Here is the content for the file `centro-medico/database/factories/PatientFactory.php`:

<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        return [
            'user_id' => null, // or you can assign a random user ID if needed
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'dui' => $this->faker->unique()->numerify('###########'), // Simulated DUI
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->optional()->address(),
        ];
    }
}