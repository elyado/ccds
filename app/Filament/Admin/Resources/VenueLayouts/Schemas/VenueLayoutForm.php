<?php

namespace App\Filament\Admin\Resources\VenueLayouts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VenueLayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Configuración')
                ->schema([
                    Select::make('venue_id')
                        ->label('Sede')
                        ->relationship('venue', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nombre de la configuración')
                        ->placeholder('Público de pie, Mesas, Teatro con sillas…')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->placeholder('publico-de-pie')
                        ->required()
                        ->maxLength(170)
                        ->helperText('Debe ser único dentro de la sede.'),

                    TextInput::make('base_capacity')
                        ->label('Capacidad máxima')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->required(),

                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'active' => 'Activa',
                            'inactive' => 'Inactiva',
                            'archived' => 'Archivada',
                        ])
                        ->default('active')
                        ->required(),

                    Textarea::make('description')
                        ->label('Descripción pública')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('technical_notes')
                        ->label('Notas técnicas internas')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}