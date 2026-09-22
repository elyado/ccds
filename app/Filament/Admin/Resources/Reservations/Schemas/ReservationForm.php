<?php

namespace App\Filament\Admin\Resources\Reservations\Schemas;

use App\Models\EventSchedule;
use App\Models\EventScheduleTicketType;
use App\Models\Reservation;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Reserva')
                ->schema([
                    TextInput::make('folio')
                        ->label('Folio')
                        ->disabled()
                        ->dehydrated()
                        ->visibleOn('edit'),

                    Select::make('event_schedule_id')
                        ->label('Función')
                        ->options(fn (): array => EventSchedule::query()
                            ->with('event')
                            ->orderByDesc('starts_at')
                            ->get()
                            ->mapWithKeys(fn (EventSchedule $schedule): array => [
                                $schedule->id => self::scheduleLabel($schedule),
                            ])
                            ->all()
                        )
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set): void {
                            $schedule = EventSchedule::find($state);

                            $set('event_schedule_ticket_type_id', null);
                            $set('unit_price', $schedule?->price_amount ?? 0);
                            self::recalculateTotal($set, 0, $schedule?->price_amount ?? 0);
                        })
                        ->required()
                        ->columnSpanFull(),

                    Select::make('event_schedule_ticket_type_id')
                        ->label('Tipo de acceso')
                        ->options(function (Get $get): array {
                            $scheduleId = $get('event_schedule_id');

                            if (! $scheduleId) {
                                return [];
                            }

                            return EventScheduleTicketType::query()
                                ->where('event_schedule_id', $scheduleId)
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->pluck('name', 'id')
                                ->all();
                        })
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                            $ticketType = EventScheduleTicketType::find($state);
                            $unitPrice = $ticketType?->price_amount ?? $get('unit_price') ?? 0;

                            $set('unit_price', $unitPrice);
                            self::recalculateTotal(
                                $set,
                                (int) ($get('quantity') ?? 0),
                                $unitPrice
                            );
                        })
                        ->helperText('Opcional. Úsalo cuando la función tenga categorías como general, cortesía o adulto mayor.'),

                    Select::make('source')
                        ->label('Origen')
                        ->options([
                            'whatsapp' => 'WhatsApp',
                            'box_office' => 'Taquilla',
                            'phone' => 'Teléfono',
                            'web' => 'Sitio web',
                            'staff' => 'Registro interno',
                        ])
                        ->default('whatsapp')
                        ->required(),

                    Select::make('status')
                        ->label('Estado de reserva')
                        ->options([
                            'active' => 'Activa',
                            'cancelled' => 'Cancelada',
                            'expired' => 'Expirada',
                        ])
                        ->default('active')
                        ->required(),

                    Select::make('payment_status')
                        ->label('Estado de pago')
                        ->options([
                            'pending' => 'Pendiente',
                            'proof_received' => 'Comprobante recibido',
                            'verified' => 'Pago verificado',
                            'complimentary' => 'Cortesía',
                            'refunded' => 'Reembolsado',
                        ])
                        ->default('pending')
                        ->required(),

                    DateTimePicker::make('expires_at')
                        ->label('Vence el')
                        ->helperText('Al expirar o cancelar, la reserva libera cupo.'),
                ])
                ->columns(2),

            Section::make('Datos de contacto')
                ->schema([
                    TextInput::make('customer_name')
                        ->label('Nombre de quien reserva')
                        ->required()
                        ->maxLength(180),

                    TextInput::make('customer_phone')
                        ->label('WhatsApp o teléfono')
                        ->tel()
                        ->required()
                        ->maxLength(40),

                    TextInput::make('customer_email')
                        ->label('Correo')
                        ->email()
                        ->maxLength(190),
                ])
                ->columns(3),

            Section::make('Accesos e importe')
                ->schema([
                    TextInput::make('quantity')
                        ->label('Número de asistentes')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->default(1)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                            self::recalculateTotal(
                                $set,
                                (int) $state,
                                $get('unit_price') ?? 0
                            );
                        })
                        ->required(),

                    TextInput::make('unit_price')
                        ->label('Precio unitario')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                            self::recalculateTotal(
                                $set,
                                (int) ($get('quantity') ?? 0),
                                $state
                            );
                        })
                        ->required(),

                    TextInput::make('total_amount')
                        ->label('Total')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->default(0),
                ])
                ->columns(3),

            Section::make('Nota interna')
                ->schema([
                    Textarea::make('internal_note')
                        ->label('Notas del equipo')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    protected static function scheduleLabel(EventSchedule $schedule): string
    {
        $title = $schedule->event?->title ?? 'Evento sin título';
        $date = $schedule->starts_at?->format('d/m/Y · H:i') ?? 'Sin fecha';

        return "{$title} · {$date}";
    }

    protected static function recalculateTotal(
        Set $set,
        int $quantity,
        int|float|string|null $unitPrice
    ): void {
        $set('total_amount', number_format(
            $quantity * (float) $unitPrice,
            2,
            '.',
            ''
        ));
    }
}