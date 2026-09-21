<?php

namespace App\Filament\Admin\Resources\VenueLayouts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VenueLayoutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('venue.name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Configuración')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('base_capacity')
                    ->label('Capacidad')
                    ->suffix(' personas')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activa',
                        'inactive' => 'Inactiva',
                        'archived' => 'Archivada',
                    ]),
            ])
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