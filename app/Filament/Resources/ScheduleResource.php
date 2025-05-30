<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestión de Citas';

    protected static ?string $navigationLabel = 'Horarios';
    protected static ?string $modelLabel = 'Horarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())
                    ->label('Paciente')
                    // ->placeholder('Ingrese su nombre')
                    ->required(),
                Forms\Components\Select::make('day')
                    ->label('Día')
                    ->placeholder('Seleccione un día')
                    ->options([
                        'lunes' => 'Lunes',
                        'martes' => 'Martes',
                        'miercoles' => 'Miércoles',
                        'jueves' => 'Jueves',
                        'viernes' => 'Viernes',
                    ])
                    ->required(),
                Forms\Components\Select::make('shift')
                    ->options([
                        'manana' => 'Mañana',
                        'tarde' => 'Tarde',
                        'noche' => 'Noche',
                    ])
                    ->placeholder('Seleccione un turno')
                    ->label('Turno')
                    ->live()
                    ->required(),
                Forms\Components\Select::make('time')
                    ->label('Hora')
                    ->placeholder('Ingrese la hora')
                    ->options(function (callable $get) {
                        $shift = $get('shift');

                        return match ($shift) {
                            'manana' => [
                                '08:00:00' => '08:00 AM',
                                '09:00:00' => '09:00 AM',
                                '10:00:00' => '10:00 AM',
                            ],
                            'tarde' => [
                                '14:00:00' => '02:00 PM',
                                '15:00:00' => '03:00 PM',
                                '16:00:00' => '04:00 PM',
                                '17:00:00' => '05:00 PM',
                            ],
                            'noche' => [
                                '18:00:00' => '06:00 PM',
                                '19:00:00' => '07:00 PM',
                                '20:00:00' => '08:00 PM',
                            ],
                            default => [],
                        };
                    })
                    ->disabled(fn (callable $get) => !$get('shift'))
                    ->live()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Día')
                    ->icon('heroicon-m-calendar-days')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('shift')
                    ->label('Turno')
                    ->icon('heroicon-m-check-circle')
                    ->iconcolor('success')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Hora')
                    ->icon('heroicon-m-clock')
                    ->iconcolor('secondary')
                    ->sortable()
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
            // ->paginated([10, 25, 50, 100, 'all']);
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];

    }
}
