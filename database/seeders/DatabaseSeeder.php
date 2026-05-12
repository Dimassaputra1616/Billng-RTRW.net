<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\InternetPackage;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== Admin User =====
        $admin = User::firstOrCreate(
            ['email' => 'admin@rtrw.net'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // ===== Internet Packages =====
        $packages = [
            ['name' => 'Paket Bronze 5 Mbps', 'speed_limit' => '5Mbps', 'price' => 75000, 'description' => 'Paket hemat untuk browsing & sosial media.'],
            ['name' => 'Paket Silver 10 Mbps', 'speed_limit' => '10Mbps', 'price' => 125000, 'description' => 'Cocok untuk streaming video & kerja dari rumah.'],
            ['name' => 'Paket Gold 20 Mbps', 'speed_limit' => '20Mbps', 'price' => 200000, 'description' => 'Kecepatan tinggi untuk keluarga & gaming.'],
            ['name' => 'Paket Platinum 50 Mbps', 'speed_limit' => '50Mbps', 'price' => 350000, 'description' => 'Ultra-fast untuk bisnis & content creator.'],
            ['name' => 'Paket Bisnis 100 Mbps', 'speed_limit' => '100Mbps', 'price' => 600000, 'description' => 'Dedicated bandwidth untuk usaha & kantor.'],
        ];

        $createdPackages = [];
        foreach ($packages as $pkg) {
            $createdPackages[] = InternetPackage::firstOrCreate(
                ['name' => $pkg['name']],
                $pkg
            );
        }

        // ===== Customers =====
        $customers = [
            ['name' => 'Budi Santoso', 'phone_number' => '081234567890', 'address' => 'Jl. Merpati No. 12, RT 03/RW 05', 'internet_package_id' => $createdPackages[2]->id, 'status' => 'active', 'pppoe_username' => 'budi_santoso', 'pppoe_password' => 'pass123', 'installation_date' => '2025-01-15'],
            ['name' => 'Siti Rahayu', 'phone_number' => '081298765432', 'address' => 'Jl. Kenari No. 7, RT 02/RW 05', 'internet_package_id' => $createdPackages[1]->id, 'status' => 'active', 'pppoe_username' => 'siti_rahayu', 'pppoe_password' => 'pass456', 'installation_date' => '2025-02-20'],
            ['name' => 'Warkop Berkah', 'phone_number' => '081355544332', 'address' => 'Jl. Raya Utama No. 25', 'internet_package_id' => $createdPackages[3]->id, 'status' => 'active', 'pppoe_username' => 'warkop_berkah', 'pppoe_password' => 'warkop789', 'installation_date' => '2025-01-10'],
            ['name' => 'Ahmad Hidayat', 'phone_number' => '081211223344', 'address' => 'Jl. Cendana No. 3, RT 01/RW 04', 'internet_package_id' => $createdPackages[0]->id, 'status' => 'active', 'pppoe_username' => 'ahmad_h', 'pppoe_password' => 'ahmad321', 'installation_date' => '2025-03-05'],
            ['name' => 'Dewi Lestari', 'phone_number' => '081399887766', 'address' => 'Jl. Flamboyan No. 18, RT 04/RW 06', 'internet_package_id' => $createdPackages[1]->id, 'status' => 'active', 'pppoe_username' => 'dewi_les', 'pppoe_password' => 'dewi654', 'installation_date' => '2025-04-12'],
            ['name' => 'Rental PS Jaya', 'phone_number' => '081266778899', 'address' => 'Jl. Pahlawan No. 55', 'internet_package_id' => $createdPackages[4]->id, 'status' => 'active', 'pppoe_username' => 'rental_ps', 'pppoe_password' => 'rental999', 'installation_date' => '2025-02-01'],
            ['name' => 'Hendra Wijaya', 'phone_number' => '081377665544', 'address' => 'Jl. Melati No. 9, RT 05/RW 03', 'internet_package_id' => $createdPackages[2]->id, 'status' => 'active', 'pppoe_username' => 'hendra_w', 'pppoe_password' => 'hendra12', 'installation_date' => '2025-05-20'],
            ['name' => 'Rina Marlina', 'phone_number' => '081244332211', 'address' => 'Jl. Dahlia No. 22, RT 03/RW 02', 'internet_package_id' => $createdPackages[0]->id, 'status' => 'inactive', 'pppoe_username' => 'rina_m', 'pppoe_password' => 'rina777', 'installation_date' => '2025-01-25'],
            ['name' => 'Toko Elektronik Maju', 'phone_number' => '081388776655', 'address' => 'Jl. Sudirman No. 100', 'internet_package_id' => $createdPackages[3]->id, 'status' => 'active', 'pppoe_username' => 'toko_maju', 'pppoe_password' => 'toko888', 'installation_date' => '2025-03-15'],
            ['name' => 'Yusuf Pratama', 'phone_number' => '081200112233', 'address' => 'Jl. Anggrek No. 14, RT 02/RW 07', 'internet_package_id' => $createdPackages[1]->id, 'status' => 'isolated', 'pppoe_username' => 'yusuf_p', 'pppoe_password' => 'yusuf444', 'installation_date' => '2025-04-01'],
            ['name' => 'Laila Fitri', 'phone_number' => '081366554433', 'address' => 'Jl. Mawar No. 6, RT 01/RW 05', 'internet_package_id' => $createdPackages[2]->id, 'status' => 'active', 'pppoe_username' => 'laila_f', 'pppoe_password' => 'laila555', 'installation_date' => '2025-06-10'],
            ['name' => 'Cafe Santai', 'phone_number' => '081277889900', 'address' => 'Jl. Veteran No. 33', 'internet_package_id' => $createdPackages[4]->id, 'status' => 'active', 'pppoe_username' => 'cafe_santai', 'pppoe_password' => 'cafe111', 'installation_date' => '2025-05-01'],
        ];

        $createdCustomers = [];
        foreach ($customers as $cust) {
            $createdCustomers[] = Customer::firstOrCreate(
                ['pppoe_username' => $cust['pppoe_username']],
                $cust
            );
        }

        // ===== Invoices & Payments for Last 3 Months =====
        $activeCustomers = Customer::where('status', 'active')->get();

        foreach ([now()->subMonths(2), now()->subMonth(), now()] as $date) {
            $month = $date->month;
            $year = $date->year;

            foreach ($activeCustomers as $customer) {
                $invoice = Invoice::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'period_month' => $month,
                        'period_year' => $year,
                    ],
                    [
                        'invoice_number' => 'INV/' . $year . '/' . str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . $customer->id . '/' . strtoupper(bin2hex(random_bytes(2))),
                        'amount' => $customer->internetPackage->price,
                        'status' => 'unpaid',
                        'due_date' => $date->copy()->day(10),
                    ]
                );

                // Pay older invoices, leave current month mostly unpaid
                if ($date->lt(now()->startOfMonth())) {
                    // Pay all past invoices
                    if ($invoice->status === 'unpaid') {
                        $methods = ['cash', 'transfer', 'qris'];
                        Payment::firstOrCreate(
                            ['invoice_id' => $invoice->id],
                            [
                                'payment_method' => $methods[array_rand($methods)],
                                'amount_paid' => $invoice->amount,
                                'payment_date' => $date->copy()->day(rand(5, 15))->setTime(rand(8, 17), rand(0, 59)),
                            ]
                        );
                        $invoice->update(['status' => 'paid']);
                    }
                } else {
                    // Current month: pay some, leave some unpaid
                    if (rand(0, 2) > 0 && $invoice->status === 'unpaid') {
                        // ~66% chance of being paid
                        $methods = ['cash', 'transfer', 'qris'];
                        Payment::firstOrCreate(
                            ['invoice_id' => $invoice->id],
                            [
                                'payment_method' => $methods[array_rand($methods)],
                                'amount_paid' => $invoice->amount,
                                'payment_date' => now()->subDays(rand(0, 7))->setTime(rand(8, 17), rand(0, 59)),
                            ]
                        );
                        $invoice->update(['status' => 'paid']);
                    }
                }
            }
        }

        // ===== Settings =====
        $settings = [
            ['key' => 'company_name', 'value' => 'RT RW NET PRO', 'group' => 'general'],
            ['key' => 'company_address', 'value' => 'Jl. Merdeka No. 1, Kelurahan Sukamaju', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '081200001111', 'group' => 'general'],
            ['key' => 'due_date_day', 'value' => '10', 'group' => 'general'],
            ['key' => 'mikrotik_host', 'value' => '192.168.88.1', 'group' => 'mikrotik'],
            ['key' => 'mikrotik_user', 'value' => 'admin', 'group' => 'mikrotik'],
            ['key' => 'mikrotik_pass', 'value' => '', 'group' => 'mikrotik'],
            ['key' => 'wa_gateway_url', 'value' => '', 'group' => 'wa'],
            ['key' => 'wa_api_key', 'value' => '', 'group' => 'wa'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info("   📦 Packages: " . InternetPackage::count());
        $this->command->info("   👥 Customers: " . Customer::count());
        $this->command->info("   📄 Invoices: " . Invoice::count());
        $this->command->info("   💰 Payments: " . Payment::count());
        $this->command->info("   ⚙️  Settings: " . Setting::count());
    }
}
