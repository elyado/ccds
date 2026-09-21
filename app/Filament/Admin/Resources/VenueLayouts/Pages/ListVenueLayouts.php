<?php

namespace App\Filament\Admin\Resources\VenueLayouts\Pages;

use App\Filament\Admin\Resources\VenueLayouts\VenueLayoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVenueLayouts extends ListRecords
{
    protected static string $resource = VenueLayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
