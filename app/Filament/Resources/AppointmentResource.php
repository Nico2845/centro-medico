<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Policies\AppointmentPolicy;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Citas';

    protected static ?string $modelLabel = 'Cita';

    protected static ?string $pluralModelLabel = 'Citas';

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', Appointment::class);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->can('update', $record);
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->can('delete', $record);
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->can('view', $record);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->label('Paciente')
                    ->options(Patient::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Médico')
                    ->options(
                        User::role('doctor')->get()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->reactive(),

                Forms\Components\DatePicker::make('appointment_date')
                    ->label('Fecha de Cita')
                    ->required()
                    ->reactive()
                    ->minDate(now()),

                Forms\Components\TimePicker::make('appointment_time')
                    ->label('Hora de Cita')
                    ->required()
                    ->seconds(false)
                    ->rules([
                        function (callable $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $doctorId = $get('user_id');
                                $date = $get('appointment_date');

                                if (!$doctorId || !$date || !$value) {
                                    return;
                                }

                                // Validación de horario del doctor
                                $dayOfWeek = strtolower(Carbon::parse($date)->locale('en')->isoFormat('dddd'));
                                $schedule = DoctorSchedule::where('user_id', $doctorId)
                                    ->where('day_of_week', $dayOfWeek)
                                    ->first();

                                if (!$schedule) {
                                    $fail('El médico no tiene horario disponible ese día.');
                                    return;
                                }

                                $time = Carbon::parse($value);
                                $start = Carbon::parse($schedule->start_time);
                                $end = Carbon::parse($schedule->end_time);

                                if ($time->lt($start) || $time->gte($end)) {
                                    $fail("El médico solo atiende de {$schedule->start_time} a {$schedule->end_time} ese día.");
                                    return;
                                }

                                \Illuminate\Support\Facades\Log::info('record route', [
                                    'record' => request()->route('record'),
                                    'type' => gettype(request()->route('record')),
                                ]);

                                // Validación de duplicados
                                $recordId = null;

                                $url = request()->headers->get('referer') ?? request()->url();
                                if (preg_match('/\/(\d+)\/edit/', $url, $matches)) {
                                    $recordId = (int) $matches[1];
                                }

                                $exists = Appointment::where('user_id', $doctorId)
                                    ->where('appointment_date', $date)
                                    ->where('appointment_time', $value)
                                    ->when($recordId, fn($q) => $q->where('id', '!=', $recordId))
                                    ->exists();

                                if ($exists) {
                                    $fail('El médico ya tiene una cita agendada en ese horario.');
                                }
                            };
                        },
                    ]),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        'completed' => 'Completada',
                    ])
                    ->required()
                    ->default('pending'),

                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Hora')
                    ->time('H:i'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                        'primary' => 'completed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        'completed' => 'Completada',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        'completed' => 'Completada',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Médico')
                    ->options(
                        User::role('doctor')->get()->pluck('name', 'id')
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasRole('doctor')) {
            return parent::getEloquentQuery()->where('user_id', $user->id);
        }

        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}