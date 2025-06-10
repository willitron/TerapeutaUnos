<?php

namespace App\Filament\Personal\Resources\ReschedulingResource\Pages;

use App\Filament\Personal\Resources\ReschedulingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReschedulings extends ListRecords
{
    protected static string $resource = ReschedulingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
