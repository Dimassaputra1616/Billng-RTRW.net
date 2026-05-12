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
                    ->money('idr')
                    ->label('Jumlah'),
                \Filament\Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->label('Tanggal'),
                \Filament\Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->label('Metode'),
            ])
            ->paginated(false);
    }
}
