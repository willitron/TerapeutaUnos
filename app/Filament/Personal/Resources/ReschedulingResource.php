<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\ReschedulingResource\Pages;
use App\Filament\Personal\Resources\ReschedulingResource\RelationManagers;
use App\Models\Rescheduling;
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

    protected static ?string $navigationLabel = 'Reagendamentos';
    protected static ?string $modelLabel = 'Reagendamiento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListReschedulings::route('/'),
            'create' => Pages\CreateRescheduling::route('/create'),
            'edit' => Pages\EditRescheduling::route('/{record}/edit'),
        ];
    }
}
