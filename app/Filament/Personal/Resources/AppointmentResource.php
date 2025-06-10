<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\AppointmentResource\Pages;
use App\Filament\Personal\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Consultas';

    protected static ?string $modelLabel = 'Consultas';

    //!PARA LAS CONSULTAS PERSONALIZADAS
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('patient_id', Auth::user()->id);
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                ->label('Paciente')
                ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())
                    ->default(Auth::user()->id)
                    // ->disabled()
                    ->hidden()
                    // ->dehydrated()
                    ->required(),
                Forms\Components\Select::make('therapist_id')
                ->label('Terapeuta')
                ->options(User::where('user_type', 'therapist')->pluck('name', 'id')->toArray())
                ->prefixicon('heroicon-o-user')
                ->hidden()
                    ->required(),
                Forms\Components\Select::make('day')
                    ->label('Día')
                    ->placeholder('Seleccione un día')
                    ->columnSpanFull()

                    ->options([
                        'lunes' => 'Lunes',
                        'martes' => 'Martes',
                        'miercoles' => 'Miércoles',
                        'jueves' => 'Jueves',
                        'viernes' => 'Viernes',
                    ])
                    ->required(),
                Forms\Components\Select::make('shift')
                    ->label('Turno')
                    ->columnSpanFull()

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
                    ->columnSpanFull()

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
                Forms\Components\Select::make('status')
                    ->label('Estado')

                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'cancelada' => 'Cancelada',
                        'realizada' => 'Realizada',
                    ])
                    ->default('pendiente')
                    ->hidden()
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->label('Fecha de Registro')
                    ->columnSpanFull()

                    ->default(now()->toDateString())
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('patient.profile_photo')
                ->circular(),
                Tables\Columns\TextColumn::make('patient.name')
                ->label('Paciente')
                ->size('md')
                // ->color('')
                ->searchable()
                ->sortable(),


                Tables\Columns\TextColumn::make('therapist.name')
                ->label('Terapeuta')
                    ->icon('heroicon-o-user')
                    ->badge()
                    ->color('success')
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->icon('heroicon-m-clock')
                    ->label('Hora')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'confirmada' => 'success',
                        'cancelada' => 'danger',
                        'realizada' => 'green',
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->icon('heroicon-m-calendar-days')
                    ->label('Fecha de Registro')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->visible(false)

                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->visible(false)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
