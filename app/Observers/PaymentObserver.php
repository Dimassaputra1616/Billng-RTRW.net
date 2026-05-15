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
        
        // 1. Sync to Mikrotik if isolated
        if ($customer && $customer->status === 'isolated') {
            $mikrotik = app(\App\Services\MikrotikService::class);
            if ($mikrotik->activateCustomer($customer->pppoe_username)) {
                $customer->update(['status' => 'active']);
            }
        }

        // 2. Send WA Receipt to Customer
        if ($customer) {
            $amount = number_format($payment->amount, 0, ',', '.');
            $number = preg_replace('/[^0-9]/', '', $customer->phone_number);
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            } elseif (str_starts_with($number, '8')) {
                $number = '62' . $number;
            }

            $message = "*Pembayaran Diterima* \n\n" .
                "Terima kasih Bapak/Ibu *{$customer->name}*,\n" .
                "Pembayaran untuk tagihan #{$invoice->invoice_number} sebesar *Rp {$amount}* telah kami terima.\n\n" .
                "Status: *LUNAS*\n" .
                "Tanggal: " . now()->format('d/m/Y H:i') . "\n\n" .
                "Terima kasih telah berlangganan VeloNet.";

            \App\Services\WhatsAppService::sendMessage($number, $message);
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
