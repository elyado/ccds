<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información pública')
                ->schema([
                    TextInput::make('title')
                        ->label('Título del evento')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(220)
                        ->unique(ignoreRecord: true)
                        ->helperText('Ejemplo: noches-acusticas-en-el-soler'),

                    Select::make('category_id')
                        ->label('Categoría')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),


                    Select::make('event_series_id')
                        ->label('Programa o ciclo')
                        ->relationship('series', 'name')
                        ->searchable()
                        ->preload()
                        ->helperText('Déjalo vacío si se trata de un evento independiente.'),


                    TextInput::make('discipline')
                        ->label('Disciplina')
                        ->placeholder('Música, teatro, cine…')
                        ->maxLength(100),

                    TextInput::make('event_type')
                        ->label('Tipo de evento')
                        ->placeholder('Concierto, función, taller…')
                        ->maxLength(100),

                    Textarea::make('summary')
                        ->label('Resumen breve')
                        ->required()
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    Textarea::make('body')
                        ->label('Descripción completa')
                        ->rows(8)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Datos para asistentes')
                ->schema([
                    Select::make('modality')
                        ->label('Modalidad')
                        ->options([
                            'in_person' => 'Presencial',
                            'online' => 'En línea',
                            'hybrid' => 'Híbrida',
                        ]),

                    TextInput::make('target_audience')
                        ->label('Público objetivo')
                        ->maxLength(150),

                    TextInput::make('recommended_age')
                        ->label('Edad recomendada')
                        ->placeholder('Todo público, +18…')
                        ->maxLength(100),

                    TextInput::make('language')
                        ->label('Idioma')
                        ->maxLength(100),

                    TextInput::make('duration_minutes')
                        ->label('Duración aproximada (minutos)')
                        ->numeric()
                        ->integer()
                        ->minValue(1),

                    Textarea::make('access_notes')
                        ->label('Notas de accesibilidad o acceso')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Precio y difusión')
                ->schema([
                    Toggle::make('is_free')
                        ->label('Evento gratuito')
                        ->default(false),

                    TextInput::make('reference_price_amount')
                        ->label('Precio desde')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('reference_price_label')
                        ->label('Texto de precio')
                        ->placeholder('Desde $150, cooperación voluntaria…')
                        ->maxLength(120),

                    TextInput::make('video_url')
                        ->label('URL de video')
                        ->url()
                        ->maxLength(2048),

                    TextInput::make('cta_label')
                        ->label('Texto del botón')
                        ->placeholder('Reservar por WhatsApp')
                        ->maxLength(100),

                    TextInput::make('cta_url')
                        ->label('URL del botón')
                        ->url()
                        ->maxLength(2048),
                ])
                ->columns(3),

            Section::make('Publicación')
                ->schema([
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

                    DateTimePicker::make('publish_starts_at')
                        ->label('Mostrar desde'),

                    DateTimePicker::make('publish_ends_at')
                        ->label('Ocultar después de'),

                    Toggle::make('is_featured')
                        ->label('Destacar evento')
                        ->default(false),

                    Toggle::make('show_on_home')
                        ->label('Mostrar en inicio')
                        ->default(false),

                    Toggle::make('show_in_archive')
                        ->label('Mostrar en archivo')
                        ->default(true),
                ])
                ->columns(3),

            Section::make('Información interna')
                ->schema([
                    Select::make('responsible_user_id')
                        ->label('Responsable')
                        ->relationship('responsibleUser', 'name')
                        ->searchable()
                        ->preload(),

                    Textarea::make('technical_requirements')
                        ->label('Requerimientos técnicos')
                        ->rows(4),

                    Textarea::make('internal_notes')
                        ->label('Notas internas')
                        ->rows(4),

                    TextInput::make('seo_title')
                        ->label('Título SEO')
                        ->maxLength(160),

                    Textarea::make('seo_description')
                        ->label('Descripción SEO')
                        ->rows(3)
                        ->maxLength(160),
                ])
                ->columns(2),
        ]);
    }
}
