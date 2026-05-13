<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentPaymentsWidget extends TableWidget
{
    protected static ?int $sort = 7;

    protected static ?string $heading = 'Pembayaran Terakhir';

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(Payment::query()->latest()->limit(5))
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('amount_paid')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->label('Jumlah'),
                \Filament\Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->label('Tanggal'),
                \Filament\Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'transfer' => 'info',
                        'qris' => 'warning', // warning is usually orange/amber, let's use a custom color if possible or just stick to filament defaults
                        default => 'gray',
                    })
                    ->label('Metode'),
            ])
            ->paginated(false);
    }
}
