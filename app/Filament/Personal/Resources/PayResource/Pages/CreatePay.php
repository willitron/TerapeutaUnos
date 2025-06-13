<?php

namespace App\Filament\Personal\Resources\PayResource\Pages;

use App\Filament\Personal\Resources\PayResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
class CreatePay extends CreateRecord
{
    protected static string $resource = PayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    // $data['user_id'] = Auth::user()->id;
    // $data['appointment_id'] = Appointment::where('id', $data['appointment_id'])->first()->id;


    $recipient = Auth::user();

    Notification::make()
            ->title('Pago Realizado')
            ->warning()
            ->body("El pago de " .$data['amount']. ' BS  en la fecha '. $data['payment_date']. "  ha sido realizado con exito")
            // ->sendToDatabase($recipient)
            ->duration(8000)
            ->send();

    return $data;
}

protected function getRedirectUrl(): string
{
    return static::getResource()::getUrl('index');
}

}
