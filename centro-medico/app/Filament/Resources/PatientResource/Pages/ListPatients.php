Here is the content for the file `ListPatients.php` located at `centro-medico/app/Filament/Resources/PatientResource/Pages/ListPatients.php`:

<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getTableColumns(): array
    {
        return [
            'full_name' => 'Nombre Completo',
            'dui' => 'DUI',
            'phone' => 'Teléfono',
            'email' => 'Email',
            'created_at' => 'Fecha de Registro',
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            'search' => 'Buscar por nombre o DUI',
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}