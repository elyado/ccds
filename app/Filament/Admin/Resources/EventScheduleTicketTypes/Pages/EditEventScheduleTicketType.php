<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages;

use App\Filament\Admin\Resources\EventScheduleTicketTypes\EventScheduleTicketTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEventScheduleTicketType extends EditRecord
{
    protected static string $resource = EventScheduleTicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
