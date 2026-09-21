<?php

namespace App\Filament\Admin\Resources\Organizations;

use App\Filament\Admin\Resources\Organizations\Pages\CreateOrganization;
use App\Filament\Admin\Resources\Organizations\Pages\EditOrganization;
use App\Filament\Admin\Resources\Organizations\Pages\ListOrganizations;
use App\Filament\Admin\Resources\Organizations\Schemas\OrganizationForm;
use App\Filament\Admin\Resources\Organizations\Tables\OrganizationsTable;
use App\Models\Organization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Organizaciones';

    protected static ?string $modelLabel = 'organización';

    protected static ?string $pluralModelLabel = 'organizaciones';

    protected static string|UnitEnum|null $navigationGroup = 'Cartelera';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return OrganizationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizations::route('/'),
            'create' => CreateOrganization::route('/create'),
            'edit' => EditOrganization::route('/{record}/edit'),
        ];
    }
}