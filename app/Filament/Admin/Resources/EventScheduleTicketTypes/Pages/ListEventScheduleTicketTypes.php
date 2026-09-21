<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages;

use App\Filament\Admin\Resources\EventScheduleTicketTypes\EventScheduleTicketTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventScheduleTicketTypes extends ListRecords
{
    protected static string $resource = EventScheduleTicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
