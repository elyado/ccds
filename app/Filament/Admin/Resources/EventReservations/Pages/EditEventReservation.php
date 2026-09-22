<?php

namespace App\Filament\Admin\Resources\EventReservations\Pages;

use App\Filament\Admin\Resources\EventReservations\EventReservationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEventReservation extends EditRecord
{
    protected static string $resource = EventReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
