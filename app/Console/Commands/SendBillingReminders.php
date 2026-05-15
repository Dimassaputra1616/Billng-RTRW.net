<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendBillingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat tagihan otomatis ke pelanggan via WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan tagihan...');

        // Cari tagihan belum lunas yang jatuh tempo hari ini atau 3 hari lagi
        $targetDates = [
            Carbon::today()->toDateString(),
            Carbon::today()->addDays(3)->toDateString(),
        ];

        $invoices = Invoice::where('status', 'unpaid')
            ->whereIn('due_date', $targetDates)
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            $this->info('Tidak ada tagihan yang perlu diingatkan hari ini.');
            return;
        }

        foreach ($invoices as $invoice) {
            $customer = $invoice->customer;
            if (!$customer || !$customer->phone_number) continue;

            $rawAmount = $invoice->amount;
            $displayAmount = ($rawAmount < 10000) ? $rawAmount * 1000 : $rawAmount;
            $amount = number_format($displayAmount, 0, ',', '.');
            $dueDate = $invoice->due_date->format('d M Y');
            $isToday = $invoice->due_date->isToday();

            $header = $isToday ? "*PENGINGAT HARI INI*" : "*PENGINGAT TAGIHAN*";
            
            $message = "{$header} \n\n" .
                "Halo Bapak/Ibu *{$customer->name}*,\n" .
                "Kami informasikan bahwa tagihan internet VeloNet Anda akan jatuh tempo pada:\n\n" .
                "*Detail Tagihan:*\n" .
                "- Nomor: #{$invoice->invoice_number}\n" .
                "- Total: *Rp {$amount}*\n" .
                "- Jatuh Tempo: *{$dueDate}* " . ($isToday ? "(HARI INI)" : "") . "\n\n" .
                "Silakan melakukan pembayaran sebelum jatuh tempo untuk menghindari isolasi jaringan otomatis.\n\n" .
                "Terima kasih atas kerja samanya.";

            // Format nomor
            $number = preg_replace('/[^0-9]/', '', $customer->phone_number);
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            } elseif (str_starts_with($number, '8')) {
                $number = '62' . $number;
            }

            $sent = WhatsAppService::sendMessage($number, $message);

            if ($sent) {
                $this->info("Berhasil mengirim pengingat ke {$customer->name}");
            } else {
                $this->error("Gagal mengirim pengingat ke {$customer->name}");
            }
        }

        $this->info('Selesai!');
    }
}
