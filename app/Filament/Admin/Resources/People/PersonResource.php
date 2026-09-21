<?php

namespace App\Filament\Admin\Resources\People;

use App\Filament\Admin\Resources\People\Pages\CreatePerson;
use App\Filament\Admin\Resources\People\Pages\EditPerson;
use App\Filament\Admin\Resources\People\Pages\ListPeople;
use App\Filament\Admin\Resources\People\Schemas\PersonForm;
use App\Filament\Admin\Resources\People\Tables\PeopleTable;
use App\Models\Person;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Personas';

    protected static ?string $modelLabel = 'persona';

    protected static ?string $pluralModelLabel = 'personas';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return PersonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeopleTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}