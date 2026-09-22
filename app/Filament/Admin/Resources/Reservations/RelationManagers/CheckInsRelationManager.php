<?php

namespace App\Filament\Admin\Resources\Reservations\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CheckInsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkIns';

    protected static ?string $title = 'Check-in';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('quantity')
                ->label('Personas que ingresan')
                ->numeric()
                ->integer()
                ->minValue(1)
                ->required(),

            Textarea::make('note')
                ->label('Nota')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quantity')
                    ->label('Ingresaron')
                    ->alignCenter(),

                TextColumn::make('checked_in_at')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i'),

                TextColumn::make('checkedInBy.name')
                    ->label('Registró')
                    ->placeholder('—'),

                TextColumn::make('note')
                    ->label('Nota')
                    ->wrap()
                    ->toggleable(),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar entrada')
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'checked_in_by' => auth()->id(),
                        'checked_in_at' => now(),
                    ])
                        ->successNotificationTitle('Entrada registrada correctamente.'),
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