<?php

namespace App\Filament\Admin\Resources\VenueLayouts\Pages;

use App\Filament\Admin\Resources\VenueLayouts\VenueLayoutResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenueLayout extends EditRecord
{
    protected static string $resource = VenueLayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
