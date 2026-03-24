<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages\CreatePatient;
use App\Filament\Resources\PatientResource\Pages\EditPatient;
use App\Filament\Resources\PatientResource\Pages\ListPatients;
use App\Models\Patient;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Gestión de Pacientes';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->label('Nombre'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Apellido'),
                Forms\Components\TextInput::make('dui')
                    ->required()
                    ->unique()
                    ->label('DUI'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->label('Teléfono'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Correo Electrónico'),
                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->label('Fecha de Nacimiento'),
                Forms\Components\Textarea::make('address')
                    ->nullable()
                    ->label('Dirección'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nombre Completo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dui')
                    ->label('DUI')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'create' => CreatePatient::route('/create'),
            'edit' => EditPatient::route('/{record}/edit'),
            'index' => ListPatients::route('/'),
        ];
    }
}