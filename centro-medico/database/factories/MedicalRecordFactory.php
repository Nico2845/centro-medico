Here is the content for the file `database/factories/MedicalRecordFactory.php`:

<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition()
    {
        return [
            'patient_id' => \App\Models\Patient::factory(),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'allergies' => $this->faker->text(100),
            'chronic_conditions' => $this->faker->text(100),
            'notes' => $this->faker->text(200),
        ];
    }
}