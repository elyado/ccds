<?php

namespace App\Filament\Admin\Resources\VenueLayouts;

use App\Filament\Admin\Resources\VenueLayouts\Pages\CreateVenueLayout;
use App\Filament\Admin\Resources\VenueLayouts\Pages\EditVenueLayout;
use App\Filament\Admin\Resources\VenueLayouts\Pages\ListVenueLayouts;
use App\Filament\Admin\Resources\VenueLayouts\Schemas\VenueLayoutForm;
use App\Filament\Admin\Resources\VenueLayouts\Tables\VenueLayoutsTable;
use App\Models\VenueLayout;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VenueLayoutResource extends Resource
{
    protected static ?string $model = VenueLayout::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'Configuraciones de aforo';

    protected static ?string $modelLabel = 'configuración de aforo';

    protected static ?string $pluralModelLabel = 'configuraciones de aforo';

    protected static string|UnitEnum|null $navigationGroup = 'Infraestructura';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return VenueLayoutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VenueLayoutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVenueLayouts::route('/'),
            'create' => CreateVenueLayout::route('/create'),
            'edit' => EditVenueLayout::route('/{record}/edit'),
        ];
    }
}