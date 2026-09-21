<?php

namespace App\Filament\Admin\Resources\People\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Perfil')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(180),

                    TextInput::make('artistic_name')
                        ->label('Nombre artístico')
                        ->maxLength(180),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true),

                    Textarea::make('bio')
                        ->label('Semblanza')
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Section::make('Contacto y enlaces')
                ->schema([
                    TextInput::make('email')
                        ->label('Correo')
                        ->email()
                        ->maxLength(190),

                    TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(40),

                    KeyValue::make('public_links')
                        ->label('Enlaces públicos')
                        ->keyLabel('Red o plataforma')
                        ->valueLabel('URL')
                        ->addActionLabel('Agregar enlace')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Publicación')
                ->schema([
                    Select::make('publication_consent_status')
                        ->label('Consentimiento para publicar')
                        ->options([
                            'pending' => 'Pendiente',
                            'granted' => 'Autorizado',
                            'denied' => 'No autorizado',
                            'revoked' => 'Revocado',
                        ])
                        ->default('pending')
                        ->required(),

                    DateTimePicker::make('publication_consented_at')
                        ->label('Fecha de autorización'),

                    Select::make('status')
                        ->label('Estado del perfil')
                        ->options([
                            'active' => 'Activo',
                            'inactive' => 'Inactivo',
                            'archived' => 'Archivado',
                        ])
                        ->default('active')
                        ->required(),
                ])
                ->columns(3),
        ]);
    }
}