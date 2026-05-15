<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\MikrotikService;
use Filament\Notifications\Notification;

class CustomerObserver
{
    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        // Cek apakah status berubah
        if ($customer->isDirty('status')) {
            $newStatus = $customer->status;
            $oldStatus = $customer->getOriginal('status');

            $service = app(MikrotikService::class);

            if ($newStatus === 'isolated' && $oldStatus !== 'isolated') {
                // Proses Isolir di Mikrotik
                $success = $service->isolateCustomer($customer);
                
                if ($success) {
                    Notification::make()
                        ->title('Pelanggan Terisolir')
                        ->body("Berhasil memutus koneksi {$customer->name} di Mikrotik.")
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Gagal Isolir Mikrotik')
                        ->body("Gagal menghubungi Mikrotik untuk pelanggan {$customer->name}. Silakan cek koneksi.")
                        ->danger()
                        ->send();
                }
            } elseif ($newStatus === 'active' && $oldStatus === 'isolated') {
                // Proses Re-Aktivasi di Mikrotik
                $success = $service->activateCustomer($customer);

                if ($success) {
                    Notification::make()
                        ->title('Pelanggan Aktif Kembali')
                        ->body("Berhasil mengaktifkan kembali koneksi {$customer->name} di Mikrotik.")
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Gagal Aktivasi Mikrotik')
                        ->body("Gagal menghubungi Mikrotik untuk pelanggan {$customer->name}.")
                        ->danger()
                        ->send();
                }
            }
        }
    }
}
