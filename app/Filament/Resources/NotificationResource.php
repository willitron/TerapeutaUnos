<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Navigation\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Notificaciones';
    protected static ?string $modelLabel = 'Notificaciones';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Select::make('user_id')
                    ->label('Paciente')
                    ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\Select::make('channel')
                    ->required()
                    ->options([
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',])
                    ->label('Canal'),


                Forms\Components\RichEditor::make('message')
                ->disableToolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ])
                    ->label('Mensaje')
                    ->required(),
                Forms\Components\DatePicker::make('notification_date')
                    ->required()
                    ->label('Fecha de notificación')
                    ->default(now()->toDateString())
                    ->readOnly(),
                Forms\Components\Section::make('status')
                    ->schema([
                        Forms\Components\Toggle::make('enviado'),
                        Forms\Components\Toggle::make('pendiente')

                    ])
                ->label('Estado'),


                // Forms\Components\Split::make([
                //     Section::make([
                //         Forms\Components\RichEditor::make('message')
                //     ->label('Mensaje')

                //     ->required(),
                //     ]),
                //     Section::make([
                //         Forms\Components\Section::make('channel')
                //         ->schema([
                //             Forms\Components\Toggle::make('email'),
                //             Forms\Components\Toggle::make('whatsapp'),

                //         ])
                //     ->label('Estado'),
                //     ])->grow(false),
                // ])->from('md'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Canal')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('notification_date')
                    ->label('Fecha de notificación')
                    ->searchable()
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->label('Estado')
                //     ,
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
