<?php

namespace App\Filament\Resources\InternetPackages\Schemas;

use Filament\Schemas\Schema;

class InternetPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Paket')
                    ->description('Informasi paket internet yang ditawarkan')
                    ->icon('heroicon-o-signal')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->placeholder('e.g. Paket Silver 10 Mbps')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('speed_limit')
                            ->label('Kecepatan')
                            ->required()
                            ->placeholder('e.g. 10Mbps')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('price')
                            ->label('Harga / Bulan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('e.g. 150000'),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Keterangan tambahan tentang paket...')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
