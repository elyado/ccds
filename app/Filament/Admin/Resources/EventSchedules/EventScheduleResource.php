<?php

namespace App\Filament\Admin\Resources\EventSchedules;

use App\Filament\Admin\Resources\EventSchedules\Pages\CreateEventSchedule;
use App\Filament\Admin\Resources\EventSchedules\Pages\EditEventSchedule;
use App\Filament\Admin\Resources\EventSchedules\Pages\ListEventSchedules;
use App\Filament\Admin\Resources\EventSchedules\Schemas\EventScheduleForm;
use App\Filament\Admin\Resources\EventSchedules\Tables\EventSchedulesTable;
use App\Filament\Admin\Resources\EventSchedules\RelationManagers\ReservationsRelationManager;
use App\Models\EventSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventScheduleResource extends Resource
{
    protected static ?string $model = EventSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Funciones';

    protected static ?string $modelLabel = 'función';

    protected static ?string $pluralModelLabel = 'funciones';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EventScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventSchedulesTable::configure($table);
    }

    public static function getRelations(): array
{
    return [
        ReservationsRelationManager::class,
    ];
}
    public static function getPages(): array
    {
        return [
            'index' => ListEventSchedules::route('/'),
            'create' => CreateEventSchedule::route('/create'),
            'edit' => EditEventSchedule::route('/{record}/edit'),
        ];
    }
}