<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DueInvoicesWidget extends TableWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = 'Pelanggan Jatuh Tempo (H-3)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Invoice::query()
                    ->where('status', 'unpaid')
                    ->whereDate('due_date', '>=', now())
                    ->whereDate('due_date', '<=', now()->addDays(3))
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan'),
                \Filament\Tables\Columns\TextColumn::make('customer.internetPackage.name')
                    ->label('Paket'),
                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->label('Tagihan'),
                \Filament\Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('Jatuh Tempo'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn () => 'Peringatan'),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('broadcast')
                    ->label('Broadcast WA Semua')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->action(fn () => \Filament\Notifications\Notification::make()
                        ->title('Broadcast Dikirim')
                        ->success()
                        ->send()),
            ]);
    }
}
