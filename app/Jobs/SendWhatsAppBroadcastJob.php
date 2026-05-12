<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Customer $customer
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logika pengiriman WA (simulasi atau integrasi API)
        // \Log::info("Mengirim WA ke {$this->customer->name} ({$this->customer->phone_number})");
    }
}
