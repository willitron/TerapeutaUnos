<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayResource\Pages;
use App\Filament\Resources\PayResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Pay;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayResource extends Resource
{
    protected static ?string $model = Pay::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Pagos';
    protected static ?string $modelLabel = 'Pago';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('appointment_id')
                    ->label('Nombre')
                    // ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())

                    ->options(
                        Appointment::with('patient')
                            ->get()
                            ->mapWithKeys(function ($appointment) {
                                return [
                                    $appointment->id => $appointment->patient->name
                                ];
                            })
                            ->toArray()
                    )

                    ->placeholder('Seleccione un paciente')
                    ->validationMessages([
                        'alpha' => 'El nombre solo puede contener letras',
                        'required' => 'El campo nombre es obligatorio',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->prefix('Bs')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'transferencia QR' => 'Transferencia QR',
                    ])
                    ->placeholder('---')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Fecha de pago')
                    ->default(now()->toDateString())
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment.patient.name')
                    ->label('Paciente')

                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método de pago')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Fecha de pago')
                    ->date()
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
            'index' => Pages\ListPays::route('/'),
            'create' => Pages\CreatePay::route('/create'),
            'edit' => Pages\EditPay::route('/{record}/edit'),
        ];
    }
}
