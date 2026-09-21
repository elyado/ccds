<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes\Schemas;

use App\Models\EventSchedule;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventScheduleTicketTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Función y tipo de acceso')
                ->schema([
                    Select::make('event_schedule_id')
                        ->label('Función')
                        ->options(fn (): array => EventSchedule::query()
                            ->with('event')
                            ->orderBy('starts_at')
                            ->get()
                            ->mapWithKeys(fn (EventSchedule $schedule): array => [
                                $schedule->id => sprintf(
                                    '%s — %s',
                                    $schedule->event?->title ?? 'Evento sin título',
                                    $schedule->starts_at?->format('d/m/Y · H:i') ?? 'Sin fecha',
                                ),
                            ])
                            ->all())
                        ->searchable()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nombre')
                        ->placeholder('General, Adulto mayor, Mesa completa…')
                        ->required()
                        ->maxLength(120),

                    TextInput::make('code')
                        ->label('Código interno')
                        ->placeholder('general, adulto-mayor, mesa-4')
                        ->required()
                        ->maxLength(60)
                        ->helperText('Debe ser único dentro de la misma función.'),

                    Select::make('access_kind')
                        ->label('Tipo de acceso')
                        ->options([
                            'paid' => 'Con costo',
                            'free' => 'Gratuito',
                            'donation' => 'Cooperación voluntaria',
                        ])
                        ->default('paid')
                        ->required(),

                    TextInput::make('price_amount')
                        ->label('Precio')
                        ->prefix('$')
                        ->numeric()
                        ->minValue(0),

                    TextInput::make('currency')
                        ->label('Moneda')
                        ->default('MXN')
                        ->maxLength(3)
                        ->required(),

                    Textarea::make('description')
                        ->label('Descripción o condiciones')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Reglas de reservación')
                ->schema([
                    Select::make('sale_unit')
                        ->label('Se reserva por')
                        ->options([
                            'person' => 'Persona',
                            'table' => 'Mesa',
                            'group' => 'Grupo',
                        ])
                        ->default('person')
                        ->required(),

                    TextInput::make('units_per_sale')
                        ->label('Personas por unidad')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->default(1)
                        ->required()
                        ->helperText('Para una mesa de cuatro personas: 4.'),

                    TextInput::make('minimum_per_purchase')
                        ->label('Mínimo por reservación')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->default(1)
                        ->required(),

                    TextInput::make('maximum_per_purchase')
                        ->label('Máximo por reservación')
                        ->numeric()
                        ->integer()
                        ->minValue(1),

                    TextInput::make('capacity_limit')
                        ->label('Límite exclusivo para este tipo')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->helperText('Déjalo vacío si comparte el aforo total de la función.'),

                    Toggle::make('is_active')
                        ->label('Disponible')
                        ->default(true),

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