<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes;

use App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages\CreateEventScheduleTicketType;
use App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages\EditEventScheduleTicketType;
use App\Filament\Admin\Resources\EventScheduleTicketTypes\Pages\ListEventScheduleTicketTypes;
use App\Filament\Admin\Resources\EventScheduleTicketTypes\Schemas\EventScheduleTicketTypeForm;
use App\Filament\Admin\Resources\EventScheduleTicketTypes\Tables\EventScheduleTicketTypesTable;
use App\Models\EventScheduleTicketType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventScheduleTicketTypeResource extends Resource
{
    protected static ?string $model = EventScheduleTicketType::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tipos de entrada';

    protected static ?string $modelLabel = 'tipo de entrada';

    protected static ?string $pluralModelLabel = 'tipos de entrada';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return EventScheduleTicketTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventScheduleTicketTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventScheduleTicketTypes::route('/'),
            'create' => CreateEventScheduleTicketType::route('/create'),
            'edit' => EditEventScheduleTicketType::route('/{record}/edit'),
        ];
    }
}