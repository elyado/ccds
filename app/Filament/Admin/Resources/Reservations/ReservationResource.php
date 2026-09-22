<?php

namespace App\Filament\Admin\Resources\Reservations;

use App\Filament\Admin\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Admin\Resources\Reservations\Pages\EditReservation;
use App\Filament\Admin\Resources\Reservations\Pages\ListReservations;
use App\Filament\Admin\Resources\Reservations\Schemas\ReservationForm;
use App\Filament\Admin\Resources\Reservations\Tables\ReservationsTable;
use App\Models\Reservation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use App\Filament\Admin\Resources\Reservations\RelationManagers\CheckInsRelationManager;
use App\Filament\Admin\Resources\Reservations\RelationManagers\PaymentsRelationManager;


class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $recordTitleAttribute = 'folio';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Reservas';

    protected static ?string $modelLabel = 'reserva';

    protected static ?string $pluralModelLabel = 'reservas';

    protected static string|UnitEnum|null $navigationGroup = 'Taquilla';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ReservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReservationsTable::configure($table);
    }
    public static function getRelations(): array
{
    return [
        PaymentsRelationManager::class,
        CheckInsRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => ListReservations::route('/'),
            'create' => CreateReservation::route('/create'),
            'edit' => EditReservation::route('/{record}/edit'),
        ];
    }
}