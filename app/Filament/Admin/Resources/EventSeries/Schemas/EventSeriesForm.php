<?php

namespace App\Filament\Admin\Resources\EventSeries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventSeriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identidad del programa')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->placeholder('Acústico a Media Luz')
                        ->required()
                        ->maxLength(180)
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->placeholder('acustico-a-media-luz')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true),

                    Select::make('type')
                        ->label('Tipo')
                        ->options([
                            'series' => 'Serie recurrente',
                            'cycle' => 'Ciclo',
                            'festival' => 'Festival',
                            'course' => 'Curso o clases',
                            'program' => 'Programa',
                        ])
                        ->default('series')
                        ->required(),

                    Select::make('category_id')
                        ->label('Categoría principal')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),

                    Textarea::make('summary')
                        ->label('Resumen breve')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Descripción completa')
                        ->rows(7)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Vigencia y publicación')
                ->schema([
                    DateTimePicker::make('starts_at')
                        ->label('Inicia'),

                    DateTimePicker::make('ends_at')
                        ->label('Termina'),

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

                    Toggle::make('is_featured')
                        ->label('Destacar programa')
                        ->default(false),

                    Toggle::make('show_on_home')
                        ->label('Mostrar en inicio')
                        ->default(false),

                    TextInput::make('sort_order')
                        ->label('Orden')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                ])
                ->columns(3),
        ]);
    }
}