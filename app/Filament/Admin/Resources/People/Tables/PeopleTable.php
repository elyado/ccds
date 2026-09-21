<?php

namespace App\Filament\Admin\Resources\People\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PeopleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('artistic_name')
                    ->label('Nombre artístico')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('publication_consent_status')
                    ->label('Consentimiento')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('publication_consent_status')
                    ->label('Consentimiento')
                    ->options([
                        'pending' => 'Pendiente',
                        'granted' => 'Autorizado',
                        'denied' => 'No autorizado',
                        'revoked' => 'Revocado',
                    ]),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
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