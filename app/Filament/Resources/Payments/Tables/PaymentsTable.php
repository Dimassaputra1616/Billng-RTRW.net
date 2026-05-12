<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer',
                        'qris' => 'QRIS',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'transfer' => 'info',
                        'qris' => 'purple',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Jumlah Bayar')
                    ->money('idr')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                \Filament\Tables\Columns\ImageColumn::make('attachment_path')
                    ->label('Bukti')
                    ->circular(),
            ])
            ->defaultSort('payment_date', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'qris' => 'QRIS',
                    ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('download_receipt')
                    ->label('Kwitansi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->tooltip('Download Kwitansi PDF')
                    ->action(fn ($record) => response()->streamDownload(function () use ($record) {
                        echo \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', ['payment' => $record])->output();
                    }, "receipt-" . str_replace('/', '-', $record->invoice->invoice_number) . ".pdf")),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pembayaran')
            ->emptyStateDescription('Pembayaran akan muncul di sini setelah pelanggan melakukan pembayaran.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
