<?php

namespace App\Filament\Admin\Resources\EventReservations;

use App\Filament\Admin\Resources\EventReservations\Pages\CreateEventReservation;
use App\Filament\Admin\Resources\EventReservations\Pages\EditEventReservation;
use App\Filament\Admin\Resources\EventReservations\Pages\ListEventReservations;
use App\Filament\Admin\Resources\EventReservations\Schemas\EventReservationForm;
use App\Filament\Admin\Resources\EventReservations\Tables\EventReservationsTable;
use App\Models\EventReservation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class EventReservationResource extends Resource
{
    protected static ?string $model = EventReservation::class;
protected static bool $shouldRegisterNavigation = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'folio';

    public static function form(Schema $schema): Schema
    {
        return EventReservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventReservationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventReservations::route('/'),
            'create' => CreateEventReservation::route('/create'),
            'edit' => EditEventReservation::route('/{record}/edit'),
        ];
    }


}
