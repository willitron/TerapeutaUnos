<?php

namespace App\Filament\Personal\Resources\AppointmentResource\Pages;

use App\Filament\Personal\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;


class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $recipient = Auth::user();

        Notification::make()
                ->title('Cambio Realizado')
                ->warning()
                ->body("La modificacion del dia " .$data['day']. ' en el turno '. $data['shift'].
                ' a horas de '. $data['time']. "  quedara pendiente de confirmacion")
                // ->sendToDatabase($recipient)
                ->duration(10000)
                ->send();

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

}
