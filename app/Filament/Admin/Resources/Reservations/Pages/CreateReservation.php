<?php

namespace App\Filament\Admin\Resources\Reservations\Pages;

use App\Filament\Admin\Resources\Reservations\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;


    protected function getCreatedNotificationTitle(): ?string
{
    return "Reserva {$this->record->folio} creada correctamente.";
}
}
