<?php

namespace App\Filament\Admin\Resources\Events\RelationManagers;

use App\Models\EventParticipant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    protected static ?string $title = 'Participantes, artistas y créditos';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('person_id')
                ->label('Persona')
                ->relationship('person', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, Set $set): void {
                    if ($state) {
                        $set('organization_id', null);
                    }
                })
                ->requiredWithout('organization_id'),

            Select::make('organization_id')
                ->label('Organización o agrupación')
                ->relationship('organization', 'name')
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, Set $set): void {
                    if ($state) {
                        $set('person_id', null);
                    }
                })
                ->requiredWithout('person_id'),

            TextInput::make('role')
                ->label('Rol')
                ->placeholder('Narradora, músico, dirección, moderador…')
                ->required()
                ->maxLength(150),

            TextInput::make('credit_text')
                ->label('Texto de crédito')
                ->placeholder('María López · Narración')
                ->maxLength(255),

            TextInput::make('sort_order')
                ->label('Orden de aparición')
                ->numeric()
                ->integer()
                ->minValue(0)
                ->default(0)
                ->required(),

            Toggle::make('is_public')
                ->label('Mostrar públicamente')
                ->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('role')
            ->columns([
                TextColumn::make('person.name')
                    ->label('Persona')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('organization.name')
                    ->label('Organización')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Rol')
                    ->searchable(),

                TextColumn::make('credit_text')
                    ->label('Crédito')
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Público')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar participante'),
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