<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $isDoctor = $user->hasRole('doctor');

        // Total Pacientes — global para todos
        $totalPatients = Patient::count();

        // Citas de hoy
        $todayQuery = Appointment::whereDate('appointment_date', Carbon::today());
        if ($isDoctor) {
            $todayQuery->where('user_id', $user->id);
        }
        $todayAppointments = $todayQuery->count();

        // Citas del mes
        $monthQuery = Appointment::whereMonth('appointment_date', Carbon::now()->month)
            ->whereYear('appointment_date', Carbon::now()->year);
        if ($isDoctor) {
            $monthQuery->where('user_id', $user->id);
        }
        $monthAppointments = $monthQuery->count();

        // Médicos activos
        $activeDoctors = User::role('doctor')->where('is_active', true)->count();

        return [
            Stat::make('Total Pacientes', $totalPatients)
                ->description('Pacientes registrados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Citas de Hoy', $todayAppointments)
                ->description($isDoctor ? 'Tus citas de hoy' : 'Todas las citas de hoy')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Citas del Mes', $monthAppointments)
                ->description($isDoctor ? 'Tus citas este mes' : 'Total del mes')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make('Médicos Activos', $activeDoctors)
                ->description('Doctores en el sistema')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
        ];
    }
}
