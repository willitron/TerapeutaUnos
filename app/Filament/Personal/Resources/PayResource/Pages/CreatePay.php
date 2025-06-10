<?php

namespace App\Filament\Personal\Resources\PayResource\Pages;

use App\Filament\Personal\Resources\PayResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePay extends CreateRecord
{
    protected static string $resource = PayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $user = auth()->user();

    if ($user->user_type === 'appointment') {
        // Aquí debes decidir cómo obtener el appointment_id relacionado.
        // Por ejemplo, si tiene uno asociado directamente:
        $data['appointment_id'] = Appointment::where('user_id', $user->id)->latest()->first()?->id;
    }

    $data['payment_date'] = now();

    return $data;
}

}
