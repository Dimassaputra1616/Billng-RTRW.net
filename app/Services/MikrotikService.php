<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Setting;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class MikrotikService
{
    protected $client;

    /**
     * Connect to Mikrotik Router
     */
    public function connect(): ?Client
    {
        try {
            $config = new Config([
                'host' => Setting::getValue('mikrotik_host', env('MIKROTIK_HOST', '192.168.1.1')),
                'user' => Setting::getValue('mikrotik_user', env('MIKROTIK_USER', 'admin')),
                'pass' => Setting::getValue('mikrotik_pass', env('MIKROTIK_PASS', '')),
                'port' => (int) Setting::getValue('mikrotik_port', env('MIKROTIK_PORT', 8728)),
                'timeout' => 3, // Faster timeout for check
            ]);

            return new Client($config);
        } catch (\Exception $e) {
            \Log::error('Mikrotik Connection Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the Mikrotik service is running in simulation/mock mode
     */
    public function isSimulationMode(): bool
    {
        try {
            $config = new Config([
                'host' => Setting::getValue('mikrotik_host', env('MIKROTIK_HOST', '192.168.1.1')),
                'user' => Setting::getValue('mikrotik_user', env('MIKROTIK_USER', 'admin')),
                'pass' => Setting::getValue('mikrotik_pass', env('MIKROTIK_PASS', '')),
                'port' => (int) Setting::getValue('mikrotik_port', env('MIKROTIK_PORT', 8728)),
                'timeout' => 1, // Quick check
            ]);
            $client = new Client($config);
            return false;
        } catch (\Exception $e) {
            return app()->environment('local');
        }
    }

    /**
     * Sync customer status to Mikrotik router
     */
    public function syncCustomer(Customer $customer): bool
    {
        if ($customer->status === 'isolated') {
            return $this->isolateCustomer($customer);
        } else {
            return $this->activateCustomer($customer);
        }
    }

    /**
     * Isolate a customer by disabling PPPoE or adding IP to address-list
     */
    public function isolateCustomer($customer): bool
    {
        if (is_string($customer)) {
            $customer = Customer::where('pppoe_username', $customer)
                ->orWhere('name', $customer)
                ->first();
        }

        if (!$customer) {
            \Log::error('Mikrotik Isolation Error: Customer not found.');
            return false;
        }

        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                \Log::info("Mock Isolation: Customer {$customer->name} isolated successfully (Local Mode)");
                return true;
            }
            return false;
        }

        try {
            // 1. PPPoE Isolation Logic
            if ($customer->pppoe_username) {
                $query = new Query('/ppp/secret/print');
                $query->where('name', $customer->pppoe_username);
                $secrets = $client->query($query)->read();

                if (!empty($secrets)) {
                    $secretId = $secrets[0]['.id'];
                    $queryDisable = new Query('/ppp/secret/set');
                    $queryDisable->equal('.id', $secretId);
                    $queryDisable->equal('disabled', 'yes');
                    $client->query($queryDisable)->read();

                    // Remove active session
                    $queryActive = new Query('/ppp/active/print');
                    $queryActive->where('name', $customer->pppoe_username);
                    $activeSessions = $client->query($queryActive)->read();
                    foreach ($activeSessions as $session) {
                        $queryRemove = new Query('/ppp/active/remove');
                        $queryRemove->equal('.id', $session['.id']);
                        $client->query($queryRemove)->read();
                    }
                }
            }

            // 2. IP-based Isolation Logic (Static IP)
            if ($customer->static_ip) {
                $queryAdd = new Query('/ip/firewall/address-list/add');
                $queryAdd->equal('list', 'ISOLATED');
                $queryAdd->equal('address', $customer->static_ip);
                $queryAdd->equal('comment', "Isolated: " . $customer->name);
                $client->query($queryAdd)->read();
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Mikrotik Isolation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactivate a customer by enabling PPPoE or removing IP from address-list
     */
    public function activateCustomer($customer): bool
    {
        if (is_string($customer)) {
            $customer = Customer::where('pppoe_username', $customer)
                ->orWhere('name', $customer)
                ->first();
        }

        if (!$customer) {
            \Log::error('Mikrotik Activation Error: Customer not found.');
            return false;
        }

        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                \Log::info("Mock Activation: Customer {$customer->name} activated successfully (Local Mode)");
                return true;
            }
            return false;
        }

        try {
            // 1. PPPoE Activation Logic
            if ($customer->pppoe_username) {
                $query = new Query('/ppp/secret/print');
                $query->where('name', $customer->pppoe_username);
                $secrets = $client->query($query)->read();

                if (!empty($secrets)) {
                    $secretId = $secrets[0]['.id'];
                    $queryEnable = new Query('/ppp/secret/set');
                    $queryEnable->equal('.id', $secretId);
                    $queryEnable->equal('disabled', 'no');
                    $client->query($queryEnable)->read();
                }
            }

            // 2. IP-based Activation Logic (Static IP)
            if ($customer->static_ip) {
                $queryPrint = new Query('/ip/firewall/address-list/print');
                $queryPrint->where('address', $customer->static_ip);
                $queryPrint->where('list', 'ISOLATED');
                $items = $client->query($queryPrint)->read();

                foreach ($items as $item) {
                    $queryRemove = new Query('/ip/firewall/address-list/remove');
                    $queryRemove->equal('.id', $item['.id']);
                    $client->query($queryRemove)->read();
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Mikrotik Activation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ping a host from Mikrotik
     */
    public function ping(string $host): array
    {
        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                return [
                    ['host' => $host, 'size' => 56, 'time' => '12ms', 'status' => 'ok'],
                    ['host' => $host, 'size' => 56, 'time' => '15ms', 'status' => 'ok'],
                    ['host' => $host, 'size' => 56, 'time' => '11ms', 'status' => 'ok'],
                    ['host' => $host, 'size' => 56, 'time' => '14ms', 'status' => 'ok'],
                ];
            }
            return [['status' => 'error', 'message' => 'Koneksi gagal']];
        }

        try {
            $query = new Query('/ping');
            $query->equal('address', $host);
            $query->equal('count', '4');
            return $client->query($query)->read();
        } catch (\Exception $e) {
            return [['status' => 'error', 'message' => $e->getMessage()]];
        }
    }

    /**
     * Execute a raw command on Mikrotik
     */
    public function executeRaw(string $command, array $params = []): array
    {
        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                $cmdClean = trim(strtolower($command));
                if (str_contains($cmdClean, 'system/identity')) {
                    return [['name' => 'VeloNet-Router-Simulated']];
                } elseif (str_contains($cmdClean, 'ip/address')) {
                    return [
                        ['.id' => '*1', 'address' => '192.168.88.1/24', 'network' => '192.168.88.0', 'interface' => 'ether1', 'actual-interface' => 'ether1', 'invalid' => 'false', 'dynamic' => 'false', 'disabled' => 'false'],
                        ['.id' => '*2', 'address' => '10.62.38.208/24', 'network' => '10.62.38.0', 'interface' => 'ether2', 'actual-interface' => 'ether2', 'invalid' => 'false', 'dynamic' => 'false', 'disabled' => 'false']
                    ];
                } elseif (str_contains($cmdClean, 'ppp/active')) {
                    return [
                        ['.id' => '*1', 'name' => 'budi_santoso', 'service' => 'pppoe', 'caller-id' => 'AA:BB:CC:DD:EE:01', 'address' => '192.168.88.10', 'uptime' => '2d5h12m'],
                        ['.id' => '*2', 'name' => 'siti_rahayu', 'service' => 'pppoe', 'caller-id' => 'AA:BB:CC:DD:EE:02', 'address' => '192.168.88.11', 'uptime' => '5d1h44m']
                    ];
                } elseif (str_contains($cmdClean, 'interface')) {
                    return [
                        ['.id' => '*1', 'name' => 'ether1', 'type' => 'ether', 'mtu' => 1500, 'running' => 'true', 'disabled' => 'false'],
                        ['.id' => '*2', 'name' => 'ether2', 'type' => 'ether', 'mtu' => 1500, 'running' => 'true', 'disabled' => 'false'],
                        ['.id' => '*3', 'name' => 'wlan1', 'type' => 'wlan', 'mtu' => 1500, 'running' => 'false', 'disabled' => 'false']
                    ];
                }
                return [['message' => 'Command simulated successfully (Mock Mode)', 'command' => $command]];
            }
            return [['status' => 'error', 'message' => 'Koneksi gagal']];
        }

        try {
            $query = new Query($command);
            foreach ($params as $key => $value) {
                $query->equal($key, $value);
            }
            return $client->query($query)->read();
        } catch (\Exception $e) {
            return [['status' => 'error', 'message' => $e->getMessage()]];
        }
    }

    /**
     * Traceroute a host from Mikrotik
     */
    public function traceroute(string $host): array
    {
        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                return [
                    ['hop' => 1, 'address' => '192.168.88.1', 'loss' => '0%', 'sent' => 1, 'last' => '1ms', 'avg' => '1ms'],
                    ['hop' => 2, 'address' => '10.0.0.1', 'loss' => '0%', 'sent' => 1, 'last' => '4ms', 'avg' => '4ms'],
                    ['hop' => 3, 'address' => '36.85.12.1', 'loss' => '0%', 'sent' => 1, 'last' => '11ms', 'avg' => '10ms'],
                    ['hop' => 4, 'address' => '180.252.4.162', 'loss' => '0%', 'sent' => 1, 'last' => '13ms', 'avg' => '14ms'],
                    ['hop' => 5, 'address' => '8.8.8.8', 'loss' => '0%', 'sent' => 1, 'last' => '12ms', 'avg' => '12ms'],
                ];
            }
            return [['status' => 'error', 'message' => 'Koneksi gagal']];
        }

        try {
            $query = new Query('/tool/traceroute');
            $query->equal('address', $host);
            $query->equal('count', '1'); 
            return $client->query($query)->read();
        } catch (\Exception $e) {
            return [['status' => 'error', 'message' => $e->getMessage()]];
        }
    }

    /**
     * Setup NAT redirect and firewall blocking for isolated customers
     */
    public function setupIsolationNAT(string $serverIp): bool
    {
        $client = $this->connect();
        if (!$client) {
            if (app()->environment('local')) {
                \Log::info("Mock NAT & Firewall Setup: Rules simulated successfully to {$serverIp} (Local Mode)");
                return true;
            }
            return false;
        }

        try {
            // A. FIREWALL FILTER RULE (Blokir Internet kecuali ke Web Billing)
            // 1. Hapus rule filter lama jika ada
            $queryFilterPrint = new Query('/ip/firewall/filter/print');
            $queryFilterPrint->where('comment', 'VeloNet Auto-Block');
            $filterItems = $client->query($queryFilterPrint)->read();

            foreach ($filterItems as $item) {
                $queryFilterRemove = new Query('/ip/firewall/filter/remove');
                $queryFilterRemove->equal('.id', $item['.id']);
                $client->query($queryFilterRemove)->read();
            }

            // 2. Tambah rule filter baru di urutan paling atas (place-before = 0)
            $queryFilterAdd = new Query('/ip/firewall/filter/add');
            $queryFilterAdd->equal('chain', 'forward');
            $queryFilterAdd->equal('src-address-list', 'ISOLATED');
            $queryFilterAdd->equal('dst-address', '!' . $serverIp);
            $queryFilterAdd->equal('action', 'drop');
            $queryFilterAdd->equal('comment', 'VeloNet Auto-Block');
            $queryFilterAdd->equal('place-before', '0'); 
            $client->query($queryFilterAdd)->read();

            // B. FIREWALL NAT RULE (Redirect Port 80 ke Halaman Isolir)
            // 1. Hapus rule NAT lama jika ada
            $queryPrint = new Query('/ip/firewall/nat/print');
            $queryPrint->where('comment', 'VeloNet Auto-Redirect');
            $items = $client->query($queryPrint)->read();

            foreach ($items as $item) {
                $queryRemove = new Query('/ip/firewall/nat/remove');
                $queryRemove->equal('.id', $item['.id']);
                $client->query($queryRemove)->read();
            }

            // 2. Tambah rule NAT baru di urutan paling atas (place-before = 0)
            $queryAdd = new Query('/ip/firewall/nat/add');
            $queryAdd->equal('chain', 'dstnat');
            $queryAdd->equal('src-address-list', 'ISOLATED');
            $queryAdd->equal('protocol', 'tcp');
            $queryAdd->equal('dst-port', '80');
            $queryAdd->equal('action', 'dst-nat');
            $queryAdd->equal('to-addresses', $serverIp);
            $queryAdd->equal('to-ports', '80');
            $queryAdd->equal('comment', 'VeloNet Auto-Redirect');
            $queryAdd->equal('place-before', '0'); 
            $client->query($queryAdd)->read();

            return true;
        } catch (\Exception $e) {
            \Log::error('Mikrotik Firewall/NAT Setup Error: ' . $e->getMessage());
            return false;
        }
    }
}
