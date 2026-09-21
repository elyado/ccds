<?php

namespace App\Filament\Admin\Resources\EventSeries;

use App\Filament\Admin\Resources\EventSeries\Pages\CreateEventSeries;
use App\Filament\Admin\Resources\EventSeries\Pages\EditEventSeries;
use App\Filament\Admin\Resources\EventSeries\Pages\ListEventSeries;
use App\Filament\Admin\Resources\EventSeries\Schemas\EventSeriesForm;
use App\Filament\Admin\Resources\EventSeries\Tables\EventSeriesTable;
use App\Models\EventSeries;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventSeriesResource extends Resource
{
    protected static ?string $model = EventSeries::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Programas y ciclos';

    protected static ?string $modelLabel = 'programa o ciclo';

    protected static ?string $pluralModelLabel = 'programas y ciclos';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return EventSeriesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventSeriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventSeries::route('/'),
            'create' => CreateEventSeries::route('/create'),
            'edit' => EditEventSeries::route('/{record}/edit'),
        ];
    }
}