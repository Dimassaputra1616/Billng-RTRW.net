<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Pengaturan')
                    ->description('Konfigurasi sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('key')
                            ->label('Kunci')
                            ->required()
                            ->disabled(fn ($record) => $record !== null)
                            ->placeholder('e.g. mikrotik_host')
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('group')
                            ->label('Grup')
                            ->options([
                                'general' => 'General',
                                'mikrotik' => 'Mikrotik',
                                'wa' => 'WhatsApp',
                            ])
                            ->default('general')
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('value')
                            ->label('Nilai')
                            ->placeholder('Masukkan nilai pengaturan...')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
