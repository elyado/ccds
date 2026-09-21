<?php

namespace App\Filament\Admin\Resources\EventSchedules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\VenueLayout;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class EventScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Evento, sede y horario')
                ->schema([
                    Select::make('event_id')
                        ->label('Evento')
                        ->relationship('event', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('venue_id')
                        ->label('Sede')
                        ->relationship('venue', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set): void {
                            $set('venue_layout_id', null);
                            $set('base_capacity_snapshot', null);
                            $set('authorized_capacity', null);
                            $set('production_capacity', 0);
                            $set('complimentary_capacity', 0);
                            $set('public_capacity', null);
                        })
                        ->required(),

                    Select::make('venue_layout_id')
                        ->label('Configuración de aforo')
                        ->relationship(
                            name: 'venueLayout',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query, Get $get): Builder => $query
                                ->where('venue_id', $get('venue_id'))
                                ->where('status', 'active'),
                        )
                        ->disabled(fn(Get $get): bool => blank($get('venue_id')))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set): void {
                            $capacity = VenueLayout::find($state)?->base_capacity;

                            $set('base_capacity_snapshot', $capacity);
                            $set('authorized_capacity', $capacity);
                            $set('production_capacity', 0);
                            $set('complimentary_capacity', 0);
                            $set('public_capacity', $capacity);
                        })
                        ->required(),

                    DateTimePicker::make('starts_at')
                        ->label('Inicio de función')
                        ->required(),

                    DateTimePicker::make('ends_at')
                        ->label('Fin de función'),

                    DateTimePicker::make('doors_at')
                        ->label('Apertura de puertas'),
                ])
                ->columns(3),

            Section::make('Aforo')
                ->description('La capacidad autorizada incluye producción, cortesías y público.')
                ->schema([
                    TextInput::make('base_capacity_snapshot')
                        ->label('Capacidad base de la configuración')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->required()
                        ->helperText('Ejemplo: 70 para Sala Pequeña con público de pie.'),

                    TextInput::make('authorized_capacity')
                        ->label('Capacidad autorizada total')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->required(),

                    TextInput::make('production_capacity')
                        ->label('Lugares para producción')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    TextInput::make('complimentary_capacity')
                        ->label('Cortesías máximas')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    TextInput::make('public_capacity')
                        ->label('Capacidad disponible al público')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->required(),

                    Toggle::make('capacity_override_authorized')
                        ->label('Autorizar aumento sobre capacidad base')
                        ->default(false)
                        ->live(),

                    Textarea::make('capacity_override_reason')
                        ->label('Motivo de la autorización')
                        ->rows(3)
                        ->visible(fn(Get $get): bool => (bool) $get('capacity_override_authorized'))
                        ->required(fn(Get $get): bool => (bool) $get('capacity_override_authorized')),

                    Select::make('capacity_override_by')
                        ->label('Autorizado por')
                        ->relationship('capacityOverrideBy', 'name')
                        ->searchable()
                        ->preload()
                        ->visible(fn(Get $get): bool => (bool) $get('capacity_override_authorized'))
                        ->required(fn(Get $get): bool => (bool) $get('capacity_override_authorized')),

                    DateTimePicker::make('capacity_override_at')
                        ->label('Fecha de autorización')
                        ->default(now())
                        ->visible(fn(Get $get): bool => (bool) $get('capacity_override_authorized'))
                        ->required(fn(Get $get): bool => (bool) $get('capacity_override_authorized')),

                    Textarea::make('capacity_override_reason')
                        ->label('Motivo de la autorización')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Acceso y precio')
                ->schema([
                    Select::make('access_type')
                        ->label('Tipo de acceso')
                        ->options([
                            'general' => 'Acceso general',
                            'reservation_only' => 'Solo con reservación',
                            'invite_only' => 'Solo invitación',
                            'private' => 'Privado',
                        ])
                        ->default('general')
                        ->required(),

                    TextInput::make('price_amount')
                        ->label('Precio')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('price_label')
                        ->label('Texto del precio')
                        ->placeholder('General $150, entrada libre…')
                        ->maxLength(120),

                    TextInput::make('currency')
                        ->label('Moneda')
                        ->default('MXN')
                        ->maxLength(3)
                        ->required(),

                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'available' => 'Disponible',
                            'sold_out' => 'Agotada',
                            'cancelled' => 'Cancelada',
                            'completed' => 'Realizada',
                            'rescheduled' => 'Reprogramada',
                        ])
                        ->default('available')
                        ->required(),

                    Toggle::make('show_capacity')
                        ->label('Mostrar capacidad total al público')
                        ->default(false),

                    Toggle::make('show_availability')
                        ->label('Mostrar disponibilidad')
                        ->default(true),
                ])
                ->columns(3),

            Section::make('Comunicación y notas')
                ->schema([
                    TextInput::make('cta_label')
                        ->label('Texto del botón')
                        ->placeholder('Reservar por WhatsApp')
                        ->maxLength(100),

                    TextInput::make('cta_url')
                        ->label('URL del botón')
                        ->url()
                        ->maxLength(2048),

                    Textarea::make('public_note')
                        ->label('Nota pública')
                        ->rows(3),

                    Textarea::make('internal_note')
                        ->label('Nota interna')
                        ->rows(3),
                ])
                ->columns(2),
        ]);
    }
}
