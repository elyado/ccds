<?php

namespace App\Filament\Admin\Resources\EventSchedules\Pages;

use App\Filament\Admin\Resources\EventSchedules\EventScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEventSchedule extends EditRecord
{
    protected static string $resource = EventScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
