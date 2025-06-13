<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\PayResource\Pages;
use App\Filament\Personal\Resources\PayResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Pay;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PayResource extends Resource
{
    protected static ?string $model = Pay::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pagos';
    protected static ?string $modelLabel = 'Pago';

 //!PARA LAS CONSULTAS PERSONALIZADAS
 public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->whereHas('appointment', function ($query) {
            $query->where('patient_id', Auth::id());
        });
}

    public static function form(Form $form): Form
    {


        return $form
        ->schema([
        Forms\Components\Select::make('appointment_id')
        ->label('Nombre')
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
        ->required(),


                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->prefix('Bs')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric(),
                    // ->disabled($isPersonal), // Solo editable por admin
                Forms\Components\Select::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        // 'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'transferencia QR' => 'Transferencia QR',
                    ])
                    ->placeholder('---')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('show_qr', true))
                    // ->visible(fn (callable $get) => $get('payment_method') === 'transferencia QR')
                    ->required(),
                    // ->disabled($isPersonal), // Solo editable por admin


                    Forms\Components\DatePicker::make('payment_date')
                    ->label('Fecha de pago')
                    ->default(now()->toDateString())
                    ->readOnly()
                    ->required(),


                    Forms\Components\Hidden::make('show_qr'),

                    Forms\Components\View::make('components.qr-preview')
    ->visible(fn (callable $get) => $get('payment_method') === 'transferencia QR'),





    //!Cuidamos con la vista que solo sea visible cuando el metodo de pago con tarjeta
    Forms\Components\Wizard::make([
        Forms\Components\Wizard\Step::make('Orden')->schema([
            Forms\Components\Select::make('Tipo de documento')
                ->label('Seleccione el tipo de documento')
                ->options([
                    'CI' => 'Carnet de Identidad',
                    'passport' => 'Pasaporte',
                ])
                ->required(),
            Forms\Components\TextInput::make('Cedula')
                ->label('Cedula de identidad')
                ->numeric()
                ->validationMessages([
                    'numeric' => 'Ingrese Datos Reales',
                ])
                ->required(),
            Forms\Components\DatePicker::make('Fecha Nac')
                ->label('Fecha de Nacimiento')
                ->date()
                ->required(),
            Forms\Components\TextInput::make('Nombre')
                ->label('Nombre')
                ->required(),
            Forms\Components\TextInput::make('ApellidoPat')
                ->label('Apellido Paterno')
                ->required(),
            Forms\Components\TextInput::make('ApellidoMat')
                ->label('Apellido Materno')
                ->required(),
            Forms\Components\TextInput::make('Correo')
                ->label('Correo Electronico')
                ->email()
                ->placeholder('mauricio@gmail.com')
                ->required(),
        ]),
        Forms\Components\Wizard\Step::make('Datos de Tarjeta')->schema([
            Forms\Components\Select::make('tipoBank')
                ->label('Seleccione su Banco')
                ->options([
                    'BNB' => 'BNB',
                    'Banco Sol' => 'BANCO SOL',
                    'BCP' => 'BCP',
                    'BANCO GANADERO' => 'BANCO GANADERO',
                    'BANCO FIE' => 'BANCO FIE',
                ])
                ->required(),
            Forms\Components\TextInput::make('numeroTarjeta')
                ->label('Número de Tarjeta')
                ->numeric()
                ->required(),
            Forms\Components\DatePicker::make('fechaTarjeta')
                ->label('Fecha de Expiración')
                ->date()
                ->required(),
            Forms\Components\TextInput::make('cvv')
                ->label('CVV')
                ->numeric()
                ->required(),
        ]),
        Forms\Components\Wizard\Step::make('Verificación')->schema([
            Forms\Components\TextInput::make('billing_code')
                ->label('Código de verificación')
                ->required(),
        ]),
        ])->visible(fn (callable $get) => $get('payment_method') === 'tarjeta')

        ->visible(function (callable $get, $livewire) {
            return $get('payment_method') === 'tarjeta'
                && ($livewire instanceof \Filament\Resources\Pages\CreateRecord
                || $livewire instanceof \Filament\Resources\Pages\EditRecord);
        })


                // Forms\Components\DatePicker::make('payment_date')
                //     ->label('Fecha de pago')
                //     ->default(now()->toDateString())
                //     ->readOnly()
                //     ->required(),
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
                // Tables\Actions\EditAction::make(),
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
