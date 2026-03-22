<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Filament\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManager;

class MedicalRecordRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalRecord';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string $resource = MedicalRecordResource::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('blood_type')
                    ->label('Blood Type')
                    ->nullable(),
                Forms\Components\Textarea::make('allergies')
                    ->label('Allergies')
                    ->nullable(),
                Forms\Components\Textarea::make('chronic_conditions')
                    ->label('Chronic Conditions')
                    ->nullable(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blood_type')->label('Blood Type'),
                Tables\Columns\TextColumn::make('allergies')->label('Allergies'),
                Tables\Columns\TextColumn::make('chronic_conditions')->label('Chronic Conditions'),
                Tables\Columns\TextColumn::make('notes')->label('Notes'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}