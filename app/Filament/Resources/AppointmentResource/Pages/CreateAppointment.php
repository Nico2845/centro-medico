<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('cancel')
            ->url($this->getResource()::getUrl('index'))
            ->color('gray'),
    ];
}
}