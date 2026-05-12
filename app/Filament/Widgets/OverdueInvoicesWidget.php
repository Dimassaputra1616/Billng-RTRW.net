<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use App\Models\Invoice;

class OverdueInvoicesWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected static ?string $heading = 'Pelanggan Menunggak (Overdue)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
                    ->where('status', 'unpaid')
                    ->whereDate('due_date', '<', now())
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
                    ->label('Jatuh Tempo')
                    ->color('danger'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn () => 'Menunggak'),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('broadcast')
                    ->label('Broadcast WA Semua')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('danger')
                    ->action(function () {
                        $customers = \App\Models\Customer::whereHas('invoices', function ($query) {
                            $query->where('status', 'unpaid')
                                ->whereDate('due_date', '<', now());
                        })->get();

                        foreach ($customers as $customer) {
                            \App\Jobs\SendWhatsAppBroadcastJob::dispatch($customer);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Broadcast WA sedang diproses di latar belakang')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
