<?php

namespace App\Filament\Resources\ReschedulingResource\Pages;

use App\Filament\Resources\ReschedulingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRescheduling extends CreateRecord
{
    protected static string $resource = ReschedulingResource::class;
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }



}




