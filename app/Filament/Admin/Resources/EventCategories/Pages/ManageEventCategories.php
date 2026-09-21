<?php

namespace App\Filament\Admin\Resources\EventCategories\Pages;

use App\Filament\Admin\Resources\EventCategories\EventCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEventCategories extends ManageRecords
{
    protected static string $resource = EventCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
