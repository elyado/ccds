<?php

namespace App\Filament\Admin\Resources\EventCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(100),

            TextInput::make('slug')
                ->label('Slug URL')
                ->required()
                ->maxLength(120)
                ->unique(ignoreRecord: true),

            ColorPicker::make('color')
                ->label('Color identificador'),

            TextInput::make('sort_order')
                ->label('Orden')
                ->numeric()
                ->integer()
                ->default(0)
                ->required(),

            Toggle::make('is_active')
                ->label('Categoría activa')
                ->default(true)
                ->required(),

            Textarea::make('description')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }
}