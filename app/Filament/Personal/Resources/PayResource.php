<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\PayResource\Pages;
use App\Filament\Personal\Resources\PayResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isPersonal = $user->user_type === 'personal';

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
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->prefix('Bs')
                    ->placeholder('0.00')
                    ->required()
                    ->numeric()
                    ->disabled($isPersonal), // Solo editable por admin
                Forms\Components\Select::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'transferencia QR' => 'Transferencia QR',
                    ])
                    ->placeholder('---')
                    ->required()
                    ->disabled($isPersonal), // Solo editable por admin
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
