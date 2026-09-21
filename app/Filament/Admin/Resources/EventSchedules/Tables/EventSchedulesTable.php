<?php

namespace App\Filament\Admin\Resources\EventSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Evento')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('starts_at')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y · H:i')
                    ->sortable(),

                TextColumn::make('venue.name')
                    ->label('Sede')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('venueLayout.name')
                    ->label('Configuración')
                    ->toggleable(),

                TextColumn::make('public_capacity')
                    ->label('Público')
                    ->suffix(' lugares')
                    ->sortable(),

                TextColumn::make('price_amount')
                    ->label('Precio')
                    ->money('MXN')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Evento')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('venue_id')
                    ->label('Sede')
                    ->relationship('venue', 'name'),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'available' => 'Disponible',
                        'sold_out' => 'Agotada',
                        'cancelled' => 'Cancelada',
                        'completed' => 'Realizada',
                        'rescheduled' => 'Reprogramada',
                    ]),
            ])
            ->defaultSort('starts_at')
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