<?php

namespace App\Filament\Admin\Resources\People\Pages;

use App\Filament\Admin\Resources\People\PersonResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;
}
