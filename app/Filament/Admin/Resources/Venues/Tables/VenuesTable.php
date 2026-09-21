<?php

// VenuesTable.php
namespace App\Filament\Admin\Resources\Venues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('area_m2')
                    ->label('Área')
                    ->suffix(' m²')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('reference_price')
                    ->label('Renta ref.')
                    ->money('MXN')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'in_review' => 'En revisión',
                        'published' => 'Publicado',
                        'unpublished' => 'No publicado',
                        'archived' => 'Archivado',
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