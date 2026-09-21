<?php

namespace App\Filament\Admin\Resources\Venues\Schemas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información general')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre de la sede')
                        ->required()
                        ->maxLength(150)
                        ->live(),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(170)
                        ->unique(ignoreRecord: true)
                        ->helperText('Ejemplo: sala-principal'),

                    TextInput::make('type')
                        ->label('Tipo de espacio')
                        ->maxLength(80)
                        ->placeholder('Teatro, sala, terraza, galería…'),

                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'draft' => 'Borrador',
                            'in_review' => 'En revisión',
                            'published' => 'Publicado',
                            'unpublished' => 'No publicado',
                            'archived' => 'Archivado',
                        ])
                        ->default('draft')
                        ->required(),

                    Textarea::make('summary')
                        ->label('Resumen breve')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Descripción completa')
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Dimensiones y capacidades')
                ->schema([
                    TextInput::make('area_m2')
                        ->label('Área (m²)')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('length_m')
                        ->label('Largo (m)')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('width_m')
                        ->label('Ancho (m)')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('height_m')
                        ->label('Altura (m)')
                        ->numeric()
                        ->minValue(0),
                ])
                ->columns(4),

            Section::make('Renta del espacio')
                ->schema([
                    TextInput::make('reference_price')
                        ->label('Precio de referencia')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('deposit_amount')
                        ->label('Depósito')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('minimum_hours')
                        ->label('Horas mínimas')
                        ->numeric()
                        ->integer()
                        ->minValue(1),

                    DateTimePicker::make('published_at')
                        ->label('Fecha de publicación'),
                ])
                ->columns(4),

            Section::make('Información técnica')
                ->schema([
                    Textarea::make('accessibility')
                        ->label('Accesibilidad')
                        ->rows(3),

                    Textarea::make('included_equipment')
                        ->label('Equipo incluido')
                        ->rows(3),

                    Textarea::make('additional_equipment')
                        ->label('Equipo adicional disponible')
                        ->rows(3),

                    Textarea::make('restrictions')
                        ->label('Restricciones')
                        ->rows(3),

                    Textarea::make('policies')
                        ->label('Políticas de uso')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Llamado a la acción')
                ->schema([
                    TextInput::make('video_url')
                        ->label('URL de video')
                        ->url()
                        ->maxLength(2048),

                    TextInput::make('cta_label')
                        ->label('Texto del botón')
                        ->maxLength(100),

                    TextInput::make('cta_url')
                        ->label('URL del botón')
                        ->url()
                        ->maxLength(2048),
                ])
                ->columns(3),
        ]);
    }
}