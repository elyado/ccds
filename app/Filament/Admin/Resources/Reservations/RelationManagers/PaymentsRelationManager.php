<?php

namespace App\Filament\Admin\Resources\Reservations\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pagos y comprobantes';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('method')
                ->label('Método')
                ->options([
                    'transfer' => 'Transferencia',
                    'cash' => 'Efectivo en taquilla',
                ])
                ->required(),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'pending' => 'Pendiente de validación',
                    'verified' => 'Verificado',
                    'rejected' => 'Rechazado',
                    'void' => 'Anulado',
                ])
                ->default('pending')
                ->required(),

            TextInput::make('amount')
                ->label('Importe recibido')
                ->numeric()
                ->prefix('$')
                ->required(),

            TextInput::make('reference')
                ->label('Referencia bancaria o recibo')
                ->maxLength(150),

            DateTimePicker::make('received_at')
                ->label('Comprobante recibido el'),

            Textarea::make('note')
                ->label('Nota interna')
                ->rows(4)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('method')
                    ->label('Método')
                    ->badge(),

                TextColumn::make('amount')
                    ->label('Importe')
                    ->money('MXN'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

                TextColumn::make('reference')
                    ->label('Referencia')
                    ->toggleable(),

                TextColumn::make('received_at')
                    ->label('Recibido')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(),

                TextColumn::make('verified_at')
                    ->label('Validado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar pago')
                    ->successNotificationTitle('Pago registrado correctamente.'),
            ])
            ->recordActions([
                EditAction::make()
                    ->successNotificationTitle('Pago actualizado correctamente.'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
