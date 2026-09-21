<?php

namespace App\Filament\Admin\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Evento')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('series.name')
                    ->label('Programa o ciclo')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('discipline')
                    ->label('Disciplina')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('reference_price_amount')
                    ->label('Precio desde')
                    ->money('MXN')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean(),

                IconColumn::make('show_on_home')
                    ->label('Inicio')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),

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
