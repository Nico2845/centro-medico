<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Citas Diarias — Últimos 30 Días';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $isDoctor = $user->hasRole('doctor');

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $query = Appointment::query()
            ->whereBetween('appointment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date');

        if ($isDoctor) {
            $query->where('user_id', $user->id);
        }

        $results = $query->pluck('total', 'date');

        // Generar todos los días del rango (incluso sin citas)
        $labels = [];
        $data = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateKey = $current->toDateString();
            $labels[] = $current->format('d M');
            $data[] = $results->get($dateKey, 0);
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => $isDoctor ? 'Mis Citas' : 'Total Citas',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
