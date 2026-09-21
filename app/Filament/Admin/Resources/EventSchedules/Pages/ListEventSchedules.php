<?php

namespace App\Filament\Admin\Resources\EventSchedules\Pages;

use App\Filament\Admin\Resources\EventSchedules\EventScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventSchedules extends ListRecords
{
    protected static string $resource = EventScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
