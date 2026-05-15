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
                if ($customer->pppoe_username) {
                    $success = $service->isolateCustomer($customer->pppoe_username);
                    
                    if ($success) {
                        Notification::make()
                            ->title('Pelanggan Terisolir')
                            ->body("Berhasil menonaktifkan PPPoE {$customer->pppoe_username} di Mikrotik.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal Isolir Mikrotik')
                            ->body("Gagal menghubungi Mikrotik untuk user {$customer->pppoe_username}. Silakan cek koneksi.")
                            ->danger()
                            ->send();
                    }
                }
            } elseif ($newStatus === 'active' && $oldStatus === 'isolated') {
                // Proses Re-Aktivasi di Mikrotik
                if ($customer->pppoe_username) {
                    $success = $service->activateCustomer($customer->pppoe_username);

                    if ($success) {
                        Notification::make()
                            ->title('Pelanggan Aktif Kembali')
                            ->body("Berhasil mengaktifkan kembali PPPoE {$customer->pppoe_username} di Mikrotik.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal Aktivasi Mikrotik')
                            ->body("Gagal menghubungi Mikrotik untuk user {$customer->pppoe_username}.")
                            ->danger()
                            ->send();
                    }
                }
            }
        }
    }
}
