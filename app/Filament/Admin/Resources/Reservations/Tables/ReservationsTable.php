<?php

namespace App\Filament\Admin\Resources\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->description(fn($record): string => $record->customer_phone)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('eventSchedule.event.title')
                    ->label('Evento')
                    ->description(fn($record): string => $record->eventSchedule?->starts_at?->format('d/m/Y · H:i') ?? '—')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Asistentes')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('verified_amount')
                    ->label('Pagado')
                    ->money('MXN')
                    ->toggleable(),

                TextColumn::make('pending_balance')
                    ->label('Saldo')
                    ->money('MXN')
                    ->color(fn($state): string => (float) $state > 0 ? 'danger' : 'success'),


                TextColumn::make('payment_status')
                    ->label('Pago')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Reserva')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'proof_received' => 'Comprobante recibido',
                        'verified' => 'Pago verificado',
                        'complimentary' => 'Cortesía',
                        'refunded' => 'Reembolsado',
                    ]),

                SelectFilter::make('status')
                    ->label('Reserva')
                    ->options([
                        'active' => 'Activa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                    ]),

                SelectFilter::make('source')
                    ->label('Origen')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'box_office' => 'Taquilla',
                        'phone' => 'Teléfono',
                        'web' => 'Sitio web',
                        'staff' => 'Registro interno',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
