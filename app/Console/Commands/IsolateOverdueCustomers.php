<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Services\MikrotikService;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class IsolateOverdueCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:isolate-overdue-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Isolate customers with overdue unpaid invoices on Mikrotik';

    /**
     * Execute the console command.
     */
    public function handle(MikrotikService $mikrotik, WhatsAppService $whatsapp)
    {
        $today = Carbon::today();
        $this->info("Checking for overdue invoices as of {$today->format('Y-m-d')}...");

        // Find active customers who have at least one unpaid invoice that is past due
        $overdueCustomers = Customer::where('status', 'active')
            ->whereHas('invoices', function ($query) use ($today) {
                $query->where('status', 'unpaid')
                    ->where('due_date', '<', $today);
            })
            ->with(['invoices' => function ($query) use ($today) {
                $query->where('status', 'unpaid')
                    ->where('due_date', '<', $today);
            }])
            ->get();

        if ($overdueCustomers->isEmpty()) {
            $this->info("No overdue customers found.");
            return;
        }

        $this->info("Found {$overdueCustomers->count()} overdue customers. Starting isolation process...");

        foreach ($overdueCustomers as $customer) {
            $this->warn("Processing Customer: {$customer->name} (PPPoE: {$customer->pppoe_username})");

            if (!$customer->pppoe_username) {
                $this->error("Skipping {$customer->name}: PPPoE Username not set.");
                continue;
            }

            // a. Isolate on Mikrotik
            $isolated = $mikrotik->isolateCustomer($customer->pppoe_username);

            if ($isolated) {
                // b. Update status in database
                $customer->update(['status' => 'isolated']);

                // c. Send WhatsApp Notification
                $overdueInvoice = $customer->invoices->first();
                $nominal = number_format($overdueInvoice->amount, 0, ',', '.');
                $message = "Mohon maaf, koneksi internet Anda sementara kami isolir karena tagihan *[{$overdueInvoice->invoice_number}]* sebesar *Rp {$nominal}* telah melewati jatuh tempo. Segera lakukan pembayaran untuk mengaktifkan kembali layanan internet Anda. Terima kasih.";
                
                $whatsapp->sendMessage($customer->phone_number, $message);

                // d. Log info
                $this->info("Customer [{$customer->name}] berhasil diisolasi.");
            } else {
                $this->error("Gagal mengisolasi Customer [{$customer->name}] di Mikrotik. Cek koneksi atau logs.");
            }
        }

        $this->info("Isolation process completed.");
    }
}
