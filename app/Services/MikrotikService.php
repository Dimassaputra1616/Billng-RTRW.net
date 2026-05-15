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
     * Isolate a customer by disabling their PPPoE Secret and removing active session
     */
    public function isolateCustomer(string $pppoe_username): bool
    {
        $client = $this->connect();
        
        if (!$client) {
            return false;
        }

        try {
            // 1. Find the secret ID
            $query = new Query('/ppp/secret/print');
            $query->where('name', $pppoe_username);
            $secrets = $client->query($query)->read();

            if (empty($secrets)) {
                return false;
            }

            $secretId = $secrets[0]['.id'];

            // 2. Disable the secret
            $queryDisable = new Query('/ppp/secret/set');
            $queryDisable->equal('.id', $secretId);
            $queryDisable->equal('disabled', 'yes');
            $client->query($queryDisable)->read();

            // 3. Find and remove active session
            $queryActive = new Query('/ppp/active/print');
            $queryActive->where('name', $pppoe_username);
            $activeSessions = $client->query($queryActive)->read();

            if (!empty($activeSessions)) {
                foreach ($activeSessions as $session) {
                    $queryRemove = new Query('/ppp/active/remove');
                    $queryRemove->equal('.id', $session['.id']);
                    $client->query($queryRemove)->read();
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Mikrotik Isolation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactive a customer by enabling their PPPoE Secret
     */
    public function activateCustomer(string $pppoe_username): bool
    {
        $client = $this->connect();
        
        if (!$client) {
            return false;
        }

        try {
            $query = new Query('/ppp/secret/print');
            $query->where('name', $pppoe_username);
            $secrets = $client->query($query)->read();

            if (empty($secrets)) {
                return false;
            }

            $secretId = $secrets[0]['.id'];

            $queryEnable = new Query('/ppp/secret/set');
            $queryEnable->equal('.id', $secretId);
            $queryEnable->equal('disabled', 'no');
            $client->query($queryEnable)->read();

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
}
