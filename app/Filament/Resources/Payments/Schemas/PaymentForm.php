<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Pembayaran')
                    ->description('Informasi pembayaran tagihan')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        \Filament\Forms\Components\Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->invoice_number} — {$record->customer->name}"),
                        \Filament\Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => '💵 Tunai',
                                'transfer' => '🏦 Transfer Bank',
                                'qris' => '📱 QRIS',
                            ])
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('amount_paid')
                            ->label('Jumlah Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        \Filament\Forms\Components\DateTimePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->default(now())
                            ->required(),
                        \Filament\Forms\Components\FileUpload::make('attachment_path')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->directory('payments')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
