Here is the content for the file `centro-medico/centro-medico/database/seeders/DatabaseSeeder.php`:

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalRecord;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed patients and their medical records
        \App\Models\Patient::factory(20)
            ->create()
            ->each(function ($patient) {
                $patient->medicalRecord()->save(
                    MedicalRecord::factory()->make()
                );
            });
    }
}