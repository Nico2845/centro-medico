<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole     = Role::create(['name' => 'admin']);
        $doctorRole    = Role::create(['name' => 'doctor']);
        $assistantRole = Role::create(['name' => 'asistente']);

        // Crear admin
        $admin = User::create([
            'name'     => 'Admin Principal',
            'email'    => 'admin@medico.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        // Crear 5 doctores con horarios
        $doctors = User::factory(5)->create()->each(function ($doctor) use ($doctorRole) {
            $doctor->assignRole($doctorRole);

            // Cada doctor tiene 3 horarios
            $days = collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
                ->shuffle()->take(3);

            foreach ($days as $day) {
                DoctorSchedule::create([
                    'user_id'    => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time'   => '11:00',
                ]);
            }
        });

        // Crear 3 asistentes
        $assistants = User::factory(3)->create()->each(function ($assistant) use ($assistantRole) {
            $assistant->assignRole($assistantRole);
        });

        // Crear 20 pacientes con expediente
        $patients = Patient::factory(20)->create()->each(function ($patient) {
            MedicalRecord::factory()->create([
                'patient_id' => $patient->id,
            ]);
        });

        // Crear 30 citas
        foreach (range(1, 30) as $i) {
            Appointment::factory()->create([
                'patient_id' => $patients->random()->id,
                'user_id'    => $doctors->random()->id,
            ]);
        }
    }
}