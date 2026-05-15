<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('phone_number')
                    ->label('No. Telepon')
                    ->searchable()
                    ->copyable()
                    ->formatStateUsing(fn ($state) => str_starts_with($state, '08') ? '628' . substr($state, 2) : $state)
                    ->icon('heroicon-o-phone'),
                \Filament\Tables\Columns\TextColumn::make('internetPackage.name')
                    ->label('Paket')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('internetPackage.speed_limit')
                    ->label('Kecepatan')
                    ->badge()
                    ->color('purple')
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                        'isolated' => 'Terisolir',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'isolated' => 'danger',
                    }),
                \Filament\Tables\Columns\TextColumn::make('installation_date')
                    ->label('Tgl. Pasang')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                        'isolated' => 'Terisolir',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('internet_package_id')
                    ->relationship('internetPackage', 'name')
                    ->label('Paket Internet')
                    ->preload(),
            ])
            ->actions([
                \Filament\Actions\Action::make('sync_mikrotik')
                    ->label('Sync')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->tooltip('Sync ke Mikrotik')
                    ->action(function ($record) {
                        $service = new \App\Services\MikrotikService();
                        if ($service->syncCustomer($record)) {
                            \Filament\Notifications\Notification::make()
                                ->title('Sync Berhasil')
                                ->body("Pelanggan {$record->name} berhasil di-sync.")
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Sync Gagal')
                                ->body('Periksa pengaturan Mikrotik di Settings.')
                                ->danger()
                                ->send();
                        }
                    }),
                \Filament\Actions\Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->tooltip('Kirim Tagihan Otomatis')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Tagihan via WA')
                    ->modalDescription('Sistem akan mengirimkan detail tagihan terbaru ke pelanggan ini secara otomatis. Lanjutkan?')
                    ->action(function ($record) {
                        $invoice = $record->invoices()
                            ->where('status', 'unpaid')
                            ->latest()
                            ->first();

                        if ($invoice) {
                            $amount = number_format($invoice->amount, 0, ',', '.');
                            $dueDate = $invoice->due_date->format('d M Y');
                            $message = "*VeloNet Billing* \n\n" .
                                "Halo Bapak/Ibu *{$record->name}*,\n" .
                                "Kami informasikan bahwa tagihan internet Anda untuk periode ini sudah terbit.\n\n" .
                                "*Detail Tagihan:*\n" .
                                "- Nomor: #{$invoice->invoice_number}\n" .
                                "- Total: *Rp {$amount}*\n" .
                                "- Jatuh Tempo: *{$dueDate}*\n\n" .
                                "Silakan melakukan pembayaran tepat waktu untuk menghindari isolasi jaringan.\n\n" .
                                "Terima kasih atas kerja samanya.";
                        } else {
                            $message = "*VeloNet Info* \n\n" .
                                "Halo Bapak/Ibu *{$record->name}*,\n" .
                                "Sekedar menyapa dari VeloNet. Pastikan koneksi internet Anda lancar jaya!\n\n" .
                                "Jika ada kendala, hubungi kami ya. Terima kasih.";
                        }

                        $number = preg_replace('/[^0-9]/', '', $record->phone_number);
                        if (str_starts_with($number, '0')) {
                            $number = '62' . substr($number, 1);
                        } elseif (str_starts_with($number, '8')) {
                            $number = '62' . $number;
                        }

                        $sent = \App\Services\WhatsAppService::sendMessage($number, $message);

                        if ($sent) {
                            \Filament\Notifications\Notification::make()
                                ->title('Tagihan Terkirim!')
                                ->body("Pesan otomatis berhasil dikirim ke {$record->name}.")
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Kirim')
                                ->body('Periksa WA Gateway atau log server.')
                                ->danger()
                                ->send();
                        }
                    }),
                \Filament\Actions\EditAction::make(),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('broadcast_wa')
                    ->label('Broadcast WA Semua')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Broadcast WhatsApp')
                    ->modalDescription('Apakah Anda yakin ingin mengirim pesan ke semua pelanggan yang belum membayar dan sudah jatuh tempo?')
                    ->action(function () {
                        $customers = \App\Models\Customer::whereHas('invoices', function ($query) {
                            $query->where('status', 'unpaid')
                                ->whereDate('due_date', '<=', now());
                        })->get();

                        foreach ($customers as $customer) {
                            \App\Jobs\SendWhatsAppBroadcastJob::dispatch($customer);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Broadcast WA sedang diproses di latar belakang')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pelanggan')
            ->emptyStateDescription('Mulai tambahkan pelanggan pertama Anda.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
