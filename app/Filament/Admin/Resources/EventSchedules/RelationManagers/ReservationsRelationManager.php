<?php

namespace App\Filament\Admin\Resources\EventSchedules\RelationManagers;

use App\Filament\Admin\Resources\Reservations\ReservationResource;
use App\Models\Reservation;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';

    protected static ?string $title = 'Taquilla y cupo de esta función';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Resumen de ocupación')
            ->description(function (): string {
                $schedule = $this->getOwnerRecord();

                $reserved = (int) $schedule->reservations()
                    ->where('status', 'active')
                    ->sum('quantity');

                $available = max(0, (int) $schedule->public_capacity - $reserved);

                $pendingPayments = $schedule->reservations()
                    ->where('status', 'active')
                    ->whereNotIn('payment_status', ['verified', 'complimentary'])
                    ->count();

                return "Cupo público: {$schedule->public_capacity} · "
                    ."Reservados: {$reserved} · "
                    ."Disponibles: {$available} · "
                    ."Pagos por validar: {$pendingPayments}";
            })
            ->columns([
                TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->description(fn (Reservation $record): string => $record->customer_phone)
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Reservó')
                    ->alignCenter(),

                TextColumn::make('checked_in_quantity')
                    ->label('Ingresaron')
                    ->alignCenter(),

                TextColumn::make('payment_status')
                    ->label('Pago')
                    ->badge(),

                TextColumn::make('pending_balance')
                    ->label('Saldo')
                    ->money('MXN')
                    ->color(fn ($state): string => (float) $state > 0 ? 'danger' : 'success'),

                TextColumn::make('status')
                    ->label('Reserva')
                    ->badge(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('open')
                    ->label('Abrir reserva')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Reservation $record): string => ReservationResource::getUrl(
                        'edit',
                        ['record' => $record]
                    )),
            ]);
    }
}