<?php

namespace App\Filament\Admin\Resources\EventSchedules\Pages;

use App\Filament\Admin\Resources\EventSchedules\EventScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventSchedule extends CreateRecord
{
    protected static string $resource = EventScheduleResource::class;
}
