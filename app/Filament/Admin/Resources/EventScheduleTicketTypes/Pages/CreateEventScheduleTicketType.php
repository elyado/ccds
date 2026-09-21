<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages;

use App\Filament\Admin\Resources\EventScheduleTicketTypes\EventScheduleTicketTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventScheduleTicketType extends CreateRecord
{
    protected static string $resource = EventScheduleTicketTypeResource::class;
}
