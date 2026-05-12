<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold')
                    ->copyable(),
                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->getStateUsing(function ($record) {
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                        return ($months[$record->period_month] ?? '') . ' ' . $record->period_year;
                    }),
                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->label('Tagihan')
                    ->money('idr')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Lunas',
                        'unpaid' => 'Belum Bayar',
                        'overdue' => 'Terlambat',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'overdue' => 'danger',
                    }),
                \Filament\Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->status === 'unpaid' && $record->due_date?->isPast() ? 'danger' : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                    ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'unpaid')
                    ->form([
                        \Filament\Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer',
                                'qris' => 'QRIS',
                            ])
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal Bayar')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->payments()->create([
                            'customer_id' => $record->customer_id,
                            'payment_method' => $data['payment_method'],
                            'amount_paid' => $record->amount,
                            'payment_date' => $data['payment_date'],
                        ]);

                        $record->update(['status' => 'paid']);

                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Berhasil')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada tagihan')
            ->emptyStateDescription('Jalankan perintah generate invoice atau buat secara manual.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
