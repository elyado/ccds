<?php

namespace App\Filament\Admin\Resources\EventSeries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventSeriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Programa o ciclo')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge(),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->toggleable(),

                TextColumn::make('starts_at')
                    ->label('Inicia')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                IconColumn::make('show_on_home')
                    ->label('Inicio')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'series' => 'Serie recurrente',
                        'cycle' => 'Ciclo',
                        'festival' => 'Festival',
                        'course' => 'Curso o clases',
                        'program' => 'Programa',
                    ]),

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