<?php

namespace App\Services;

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
                'timeout' => 5,
            ]);

            return new Client($config);
        } catch (\Exception $e) {
            \Log::error('Mikrotik Connection Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Isolate a customer by disabling PPPoE or adding IP to address-list
     */
    public function isolateCustomer(Customer $customer): bool
    {
        $client = $this->connect();
        if (!$client) return false;

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
     * Reactive a customer by enabling PPPoE or removing IP from address-list
     */
    public function activateCustomer(Customer $customer): bool
    {
        $client = $this->connect();
        if (!$client) return false;

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
        if (!$client) return [['status' => 'error', 'message' => 'Koneksi gagal']];

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
        if (!$client) return [['status' => 'error', 'message' => 'Koneksi gagal']];

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
        if (!$client) return [['status' => 'error', 'message' => 'Koneksi gagal']];

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
     * Setup NAT redirect for isolated customers
     */
    public function setupIsolationNAT(string $serverIp): bool
    {
        $client = $this->connect();
        if (!$client) return false;

        try {
            // 1. Remove existing redirect if any
            $queryPrint = new Query('/ip/firewall/nat/print');
            $queryPrint->where('comment', 'VeloNet Auto-Redirect');
            $items = $client->query($queryPrint)->read();

            foreach ($items as $item) {
                $queryRemove = new Query('/ip/firewall/nat/remove');
                $queryRemove->equal('.id', $item['.id']);
                $client->query($queryRemove)->read();
            }

            // 2. Add new NAT Redirect Rule
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
            \Log::error('Mikrotik NAT Setup Error: ' . $e->getMessage());
            return false;
        }
    }
}
