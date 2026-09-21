<?php

namespace App\Filament\Admin\Resources\Organizations\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Perfil')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(220)
                        ->unique(ignoreRecord: true),

                    TextInput::make('type')
                        ->label('Tipo de organización')
                        ->placeholder('Banda, compañía, colectivo, institución…')
                        ->maxLength(80),

                    Textarea::make('summary')
                        ->label('Resumen breve')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Descripción completa')
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Enlaces y publicación')
                ->schema([
                    TextInput::make('website_url')
                        ->label('Sitio web')
                        ->url()
                        ->maxLength(2048),

                    KeyValue::make('public_links')
                        ->label('Redes y enlaces')
                        ->keyLabel('Red o plataforma')
                        ->valueLabel('URL')
                        ->addActionLabel('Agregar enlace')
                        ->columnSpanFull(),

                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'active' => 'Activa',
                            'inactive' => 'Inactiva',
                            'archived' => 'Archivada',
                        ])
                        ->default('active')
                        ->required(),
                ])
                ->columns(2),
        ]);
    }
}