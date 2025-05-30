<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReschedulingResource\Pages;
use App\Filament\Resources\ReschedulingResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Rescheduling;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReschedulingResource extends Resource
{
    protected static ?string $model = Rescheduling::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Gestión de Citas';


    protected static ?string $navigationLabel = 'Reagendamentos';
    protected static ?string $modelLabel = 'Reagendamiento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->label('Paciente')
                    // ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())
                    ->options(User::where('user_type', 'patient')->pluck('name', 'id'))
                    // ->live()
                    ->reactive()
                    ->required(),

                // Forms\Components\Select::make('id_original_appointment')
                // ->label('Cita original')


                //     ->reactive()
                //     ->options(function (callable $get) {
                //         $patientId = $get('patient_id');

                //         if (!$patientId) {
                //             return [];
                //         }

                //         return Appointment::where('patient_id', $patientId)
                //             ->get()
                //             ->mapWithKeys(fn ($appointment) => [
                //                 $appointment->id => $appointment->appointment_date . ' ' . $appointment->time,
                //             ])
                //             ->toArray();
                //     })
                //     ->afterStateUpdated(function (callable $set, $state) {
                //         $appointment = Appointment::find($state);
                //         if ($appointment) {
                //             $set('appointment_date', $appointment->appointment_date);
                //         }
                //     })
                //     ->required(),

                // Forms\Components\DatePicker::make('id_new_appointment')
                //     ->label('Cita nueva')
                //     ->native(false)
                //     ->firstDayOfWeek(5)
                //     ->required(),

                Forms\Components\Select::make('id_original_appointment')
                ->label('Cita original')
                ->reactive()
                ->options(function (callable $get) {
                    $patientId = $get('patient_id');

                    if (!$patientId) {
                        return [];
                    }

                    return Appointment::where('patient_id', $patientId)
                        ->get()
                        ->mapWithKeys(fn ($appointment) => [
                            $appointment->id => $appointment->appointment_date
                        ])
                        ->toArray();
                })
                ->afterStateUpdated(function (callable $set, $state) {
                    $appointment = Appointment::find($state);
                    if ($appointment) {
                        // Aquí cambiamos para que copie al campo correcto: id_new_appointment
                        $set('id_new_appointment', $appointment->appointment_date);
                    }
                })
                ->required(),

                Forms\Components\DatePicker::make('id_new_appointment')
                ->label('Cita nueva')
                ->native(false)
                ->firstDayOfWeek(5)
                ->required(),




                Forms\Components\Textarea::make('reason')
                    ->label('Motivo')
                    ->columnSpanFull(),

            ])
            ->columns(3);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_original_appointment')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_new_appointment')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReschedulings::route('/'),
            'create' => Pages\CreateRescheduling::route('/create'),
            'edit' => Pages\EditRescheduling::route('/{record}/edit'),
        ];
    }
}
