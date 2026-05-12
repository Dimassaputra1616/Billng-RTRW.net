<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $invoice = $payment->invoice;
        $invoice->update(['status' => 'paid']);

        $customer = $invoice->customer;
        if ($customer && $customer->status === 'isolated') {
            $mikrotik = app(\App\Services\MikrotikService::class);
            if ($mikrotik->activateCustomer($customer->pppoe_username)) {
                $customer->update(['status' => 'active']);
                
                // Optionally send a thank you / reactivation message
                $whatsapp = app(\App\Services\WhatsAppService::class);
                $whatsapp->sendMessage($customer->phone_number, "Terima kasih! Pembayaran tagihan *[{$invoice->invoice_number}]* telah kami terima. Layanan internet Anda telah diaktifkan kembali secara otomatis. Selamat menikmati layanan kami kembali.");
            }
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        // If payment is deleted, revert invoice status if no other payments exist
        if ($payment->invoice->payments()->count() === 0) {
            $payment->invoice->update(['status' => 'unpaid']);
        }
    }
}
