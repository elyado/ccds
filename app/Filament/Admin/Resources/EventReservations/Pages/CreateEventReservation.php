<?php

namespace App\Filament\Admin\Resources\EventReservations\Pages;

use App\Filament\Admin\Resources\EventReservations\EventReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventReservation extends CreateRecord
{
    protected static string $resource = EventReservationResource::class;
}
