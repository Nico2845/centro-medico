<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MedicalRecordRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalRecord';
    protected static ?string $title = 'Expediente Médico';
    protected static ?string $modelLabel = 'Expediente';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos Clínicos')
                    ->schema([
                        Forms\Components\Select::make('blood_type')
                            ->label('Tipo de Sangre')
                            ->options([
                                'A+' => 'A+', 'A-' => 'A-',
                                'B+' => 'B+', 'B-' => 'B-',
                                'AB+' => 'AB+', 'AB-' => 'AB-',
                                'O+' => 'O+', 'O-' => 'O-',
                            ])
                            ->searchable()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('allergies')
                            ->label('Alergias')
                            ->rows(2)
                            ->columnSpanFull()
                            ->disabled(fn() => auth()->user()->hasRole('asistente')),

                        Forms\Components\Textarea::make('medical_history')
                            ->label('Historial Médico')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(fn() => auth()->user()->hasRole('asistente')),

                        Forms\Components\Textarea::make('current_medications')
                            ->label('Medicamentos Actuales')
                            ->rows(2)
                            ->columnSpanFull()
                            ->disabled(fn() => auth()->user()->hasRole('asistente')),

                        Forms\Components\RichEditor::make('notes')
                            ->label('Notas Generales')
                            ->columnSpanFull()
                            ->disabled(fn() => auth()->user()->hasRole('asistente')),
                    ]),
            ]);
    }

    // infolist() y table() — sin cambios, estaban perfectos
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información Clínica')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('blood_type')
                            ->label('Tipo de Sangre')
                            ->badge()
                            ->color('danger'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('allergies')
                            ->label('Alergias')
                            ->columnSpanFull()
                            ->placeholder('Sin alergias registradas'),

                        Infolists\Components\TextEntry::make('medical_history')
                            ->label('Historial Médico')
                            ->columnSpanFull()
                            ->placeholder('Sin historial registrado'),

                        Infolists\Components\TextEntry::make('current_medications')
                            ->label('Medicamentos Actuales')
                            ->columnSpanFull()
                            ->placeholder('Sin medicamentos registrados'),

                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notas Generales')
                            ->html()
                            ->columnSpanFull()
                            ->placeholder('Sin notas'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('blood_type')
            ->columns([
                Tables\Columns\TextColumn::make('blood_type')
                    ->label('Tipo de Sangre'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn() =>
                        $this->getOwnerRecord()->medicalRecord === null
                            && auth()->user()->hasAnyRole(['admin', 'asistente'])
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn() =>
                        auth()->user()->hasAnyRole(['admin', 'doctor'])
                    ),
            ])
            ->bulkActions([]);
    }
}