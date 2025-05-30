<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestión de Usuarios';


    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuarios';

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::where('user_type', 'patient')->get()-> count();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    // ->alpha()
                    ->regex('/^[\pL\s\-]+$/u') // Letras, espacios y guiones
                    ->required()
                    // ->extraAttributes(['style' => 'white-space: normal; word-break: break-word;'])
                    ->columnSpanFull()
                    ->validationMessages([
                        'regex' => 'El nombre solo puede contener letras',
                        'required' => 'El campo nombre es obligatorio',
                    ])
                    ->maxLength(255),
                    Forms\Components\TextInput::make('apellido_paterno')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('apellido_materno')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->startsWith(['7','6'])
                    ->maxLength(8),
                Forms\Components\Select::make('user_type')
                    ->label('Tipo de usuario')
                    ->options([
                        'patient' => 'Paciente',
                        'therapist' => 'Terapeuta',
                    ])
                    ->required(),
                // Forms\Components\DateTimePicker::make('email_verified_at'),

                // Forms\Components\TextInput::make('password')
                //     ->password()
                //     ->required()
                //     ->maxLength(255),

                 // SOLO mostrar el campo de password si estás en creación o si quieres cambiarlo
            Forms\Components\TextInput::make('password')
            ->label('Contraseña')
            ->password()
            ->revealable()
            ->maxLength(255)
            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null) // solo guarda si hay valor
            ->dehydrated(fn ($state) => filled($state)) // evita guardar nulo
            ->required(fn (string $context) => $context === 'create') // solo obligatorio en 'create'
            ->helperText('Déjalo vacío si no deseas cambiar la contraseña'),

                Forms\Components\FileUpload::make('profile_photo')
                    ->label('Foto de perfil')
                    ->nullable()
                    ->imageEditor()
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->icon('heroicon-o-user')
                    ->searchable(),

                Tables\Columns\TextColumn::make('apellido_paterno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_materno')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->icon('heroicon-m-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->icon('heroicon-m-user-group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'therapist' => 'warning',
                        'patient' => 'success',
                        'admin' => 'danger',
                    })
                    ->label('Tipo de usuario'),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Actions\ViewAction::make()->label('Ver'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make() ->label('Eliminar'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
