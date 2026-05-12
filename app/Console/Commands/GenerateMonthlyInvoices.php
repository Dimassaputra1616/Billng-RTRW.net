<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-monthly-invoices {--all} {--force}')]
#[Description('Generate monthly invoices. Use --all to ignore billing_day filter, --force to overwrite existing invoices.')]
class GenerateMonthlyInvoices extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = now()->month;
        $year = now()->year;
        $today = now()->day;

        $useAll = $this->option('all');
        $useForce = $this->option('force');

        // Build query: filter by active status, optionally filter by billing_day
        $query = \App\Models\Customer::where('status', 'active');

        if (!$useAll) {
            $query->where('billing_day', $today);
        }

        $customers = $query->get();

        if ($useAll) {
            $this->info("Mode --all: mengambil semua customer aktif ({$customers->count()} customer).");
        }
        if ($useForce) {
            $this->info("Mode --force: invoice yang sudah ada akan dihapus dan dibuat ulang.");
        }

        $count = 0;

        foreach ($customers as $customer) {
            // Check if already exists for this period
            $existingInvoice = \App\Models\Invoice::where('customer_id', $customer->id)
                ->where('period_month', $month)
                ->where('period_year', $year)
                ->first();

            if ($existingInvoice && !$useForce) {
                // Skip, invoice sudah ada dan tidak pakai --force
                continue;
            }

            if ($existingInvoice && $useForce) {
                // Hapus invoice lama lalu create ulang
                $existingInvoice->delete();
                $this->warn("Deleted existing invoice [{$existingInvoice->invoice_number}] for [{$customer->name}].");
            }

            $invoice = \App\Models\Invoice::create([
                'customer_id' => $customer->id,
                'invoice_number' => 'INV/' . $year . '/' . str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . $customer->id . '/' . strtoupper(bin2hex(random_bytes(2))),
                'period_month' => $month,
                'period_year' => $year,
                'amount' => $customer->internetPackage->price,
                'status' => 'unpaid',
                'due_date' => now()->day($customer->billing_day),
            ]);

            // Kirim Notifikasi WhatsApp
            $message = "Halo *{$customer->name}*, tagihan internet RT RW NET PRO untuk periode bulan ini telah terbit.\n" .
                      "- No. Tagihan: *{$invoice->invoice_number}*\n" .
                      "- Total Tagihan: *Rp " . number_format($invoice->amount, 0, ',', '.') . "*\n" .
                      "- Jatuh Tempo: *{$invoice->due_date->format('d/m/Y')}*\n\n" .
                      "Mohon segera melakukan pembayaran. Abaikan pesan ini jika sudah membayar. Terima kasih.";

            \App\Services\WhatsAppService::sendMessage($customer->phone_number, $message);

            $this->info("Success generate invoice for customer: [{$customer->name}]");
            $count++;
        }

        if ($count > 0) {
            $this->info("Total {$count} invoices generated.");
        } else {
            $this->info("No invoices needed to be generated today.");
        }
    }
}
