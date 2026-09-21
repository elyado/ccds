<?php

namespace App\Filament\Admin\Resources\EventCategories;

use App\Filament\Admin\Resources\EventCategories\Pages\ManageEventCategories;
use App\Filament\Admin\Resources\EventCategories\Schemas\EventCategoryForm;
use App\Filament\Admin\Resources\EventCategories\Tables\EventCategoriesTable;
use App\Models\EventCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventCategoryResource extends Resource
{
    protected static ?string $model = EventCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categorías';

    protected static ?string $modelLabel = 'categoría';

    protected static ?string $pluralModelLabel = 'categorías';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EventCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEventCategories::route('/'),
        ];
    }
}
