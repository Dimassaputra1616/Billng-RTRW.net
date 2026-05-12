<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Tagihan')
                    ->description('Informasi tagihan pelanggan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        \Filament\Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('invoice_number')
                            ->label('No. Invoice')
                            ->required()
                            ->default(fn () => 'INV/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '/' . strtoupper(bin2hex(random_bytes(3))))
                            ->maxLength(255),
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                \Filament\Forms\Components\Select::make('period_month')
                                    ->label('Bulan')
                                    ->options([
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                                    ])
                                    ->default(now()->month)
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('period_year')
                                    ->label('Tahun')
                                    ->numeric()
                                    ->default(now()->year)
                                    ->required(),
                            ]),
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Tagihan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'paid' => 'Lunas',
                                'overdue' => 'Terlambat',
                            ])
                            ->default('unpaid')
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->default(now()->day(10))
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
