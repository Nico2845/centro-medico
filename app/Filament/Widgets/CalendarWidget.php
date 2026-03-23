<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 3;

    public function fetchEvents(array $info): array
    {
        $events = [];

        $dayMap = [
            'sunday'    => 0,
            'monday'    => 1,
            'tuesday'   => 2,
            'wednesday' => 3,
            'thursday'  => 4,
            'friday'    => 5,
            'saturday'  => 6,
        ];

        $start = Carbon::parse($info['start']);
        $end   = Carbon::parse($info['end']);

        // Horarios de doctores como eventos recurrentes (verdes)
        $schedules = DoctorSchedule::with('doctor')->get();

        foreach ($schedules as $schedule) {
            $dayNumber = $dayMap[$schedule->day_of_week] ?? null;
            if ($dayNumber === null) continue;

            $current = $start->copy();
            while ($current->lte($end)) {
                if ($current->dayOfWeek === $dayNumber) {
                    $events[] = [
                        'id'              => 'schedule-' . $schedule->id . '-' . $current->toDateString(),
                        'title'           => ($schedule->doctor->name ?? 'Doctor') . ' disponible',
                        'start'           => $current->toDateString() . 'T' . $schedule->start_time,
                        'end'             => $current->toDateString() . 'T' . $schedule->end_time,
                        'backgroundColor' => '#22c55e',
                        'borderColor'     => '#16a34a',
                    ];
                }
                $current->addDay();
            }
        }

        // Citas existentes (coloreadas por estado)
        $appointments = Appointment::with('patient', 'doctor')
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        foreach ($appointments as $appointment) {
            $events[] = [
                'id'              => 'appt-' . $appointment->id,
                'title'           => ($appointment->patient->name ?? 'Paciente') . ' — Dr. ' . ($appointment->doctor->name ?? ''),
                'start'           => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor'     => $this->getStatusColor($appointment->status),
            ];
        }

        return $events;
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'confirmed' => '#3b82f6',
            'pending'   => '#f59e0b',
            'cancelled' => '#ef4444',
            'completed' => '#6b7280',
            default     => '#8b5cf6',
        };
    }
}
