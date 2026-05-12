<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pribadi')
                    ->description('Data dasar pelanggan')
                    ->icon('heroicon-o-user')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->placeholder('e.g. Budi Santoso')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('phone_number')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->prefix('+62')
                            ->placeholder('81234567890')
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->formatStateUsing(fn ($state) => preg_replace('/^62/', '', $state))
                            ->dehydrateStateUsing(fn ($state) => '62' . $state)
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->placeholder('Alamat lengkap pelanggan...')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Pengaturan Internet')
                    ->description('Detail paket dan koneksi ke router')
                    ->icon('heroicon-o-signal')
                    ->schema([
                        \Filament\Forms\Components\Select::make('internet_package_id')
                            ->label('Paket Internet')
                            ->relationship('internetPackage', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->label('Nama Paket')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('speed_limit')
                                    ->label('Kecepatan')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ]),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Non-Aktif',
                                'isolated' => 'Terisolir',
                            ])
                            ->default('active')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('pppoe_username')
                            ->label('PPPoE Username')
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. client_budi')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('pppoe_password')
                            ->label('PPPoE Password')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('static_ip')
                            ->label('Static IP')
                            ->ipv4()
                            ->placeholder('e.g. 192.168.1.100')
                            ->maxLength(255),
                        \Filament\Forms\Components\DatePicker::make('installation_date')
                            ->label('Tanggal Pasang')
                            ->default(now()),
                        \Filament\Forms\Components\TextInput::make('billing_day')
                            ->label('Tanggal Jatuh Tempo (Tgl 1-28)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(28)
                            ->default(10)
                            ->required()
                            ->helperText('Tanggal tagihan setiap bulannya'),
                    ])->columns(2),
            ]);
    }
}
