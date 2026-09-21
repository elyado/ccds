<?php

namespace App\Filament\Admin\Resources\EventScheduleTicketTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventScheduleTicketTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('schedule.event.title')
                    ->label('Evento')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('schedule.starts_at')
                    ->label('Función')
                    ->dateTime('d/m/Y · H:i')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Entrada')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('access_kind')
                    ->label('Acceso')
                    ->badge(),

                TextColumn::make('price_amount')
                    ->label('Precio')
                    ->money('MXN'),

                TextColumn::make('sale_unit')
                    ->label('Reserva por')
                    ->badge()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('access_kind')
                    ->label('Acceso')
                    ->options([
                        'paid' => 'Con costo',
                        'free' => 'Gratuito',
                        'donation' => 'Cooperación voluntaria',
                    ]),
            ])
            ->defaultSort('sort_order')
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