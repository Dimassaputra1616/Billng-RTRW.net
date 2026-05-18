<?php

namespace App\Filament\Pages;

use App\Services\MikrotikService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class NetworkTerminal extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Terminal Mikrotik';

    protected static ?string $title = 'Network Terminal';

    protected static string | \UnitEnum | null $navigationGroup = 'Layanan Network';

    protected string $view = 'filament.pages.network-terminal';

    public ?array $data = [];
    public string $output = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Mikrotik Command Center')
                ->description('Eksekusi perintah ping atau CLI langsung ke router.')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('ping_host')
                                ->label('Ping Host / IP')
                                ->placeholder('8.8.8.8')
                                ->default('8.8.8.8')
                                ->required()
                                ->helperText('Untuk mengetes koneksi.'),
                            
                            TextInput::make('raw_command')
                                ->label('Raw Command')
                                ->placeholder('/ip/address/print')
                                ->default('/ip/address/print')
                                ->helperText('Masukkan path API Mikrotik.'),

                            TextInput::make('server_ip')
                                ->label('IP Server Billing')
                                ->placeholder('10.62.38.208')
                                ->default('10.62.38.208')
                                ->helperText('Untuk redirect halaman isolir.'),
                        ]),
                ])->statePath('data'),
        ];
    }

    public function runPing(): void
    {
        $host = $this->data['ping_host'] ?? '8.8.8.8';

        $this->output = "Pinging $host from Mikrotik...\n";
        
        $service = app(MikrotikService::class);
        $isSimulated = $service->isSimulationMode();
        
        if ($isSimulated) {
            $this->output = "[SIMULASI - TIDAK ADA KONEKSI ROUTER]\n" . $this->output;
        }
        
        $results = $service->ping($host);

        if (isset($results[0]['status']) && $results[0]['status'] === 'error') {
            $this->output .= "Error: " . $results[0]['message'];
            return;
        }

        foreach ($results as $res) {
            $this->output .= "Host: {$res['host']} | size: {$res['size']} | time: {$res['time']} | status: " . ($res['status'] ?? 'ok') . "\n";
        }
    }

    public function runCommand(): void
    {
        $cmd = $this->data['raw_command'] ?? '/ip/address/print';

        $this->output = "Executing $cmd...\n";

        $service = app(MikrotikService::class);
        $isSimulated = $service->isSimulationMode();
        
        if ($isSimulated) {
            $this->output = "[SIMULASI - TIDAK ADA KONEKSI ROUTER]\n" . $this->output;
        }
        
        $results = $service->executeRaw($cmd);

        $this->output .= json_encode($results, JSON_PRETTY_PRINT);
    }

    public function runTraceroute(): void
    {
        $host = $this->data['ping_host'] ?? '8.8.8.8';

        $this->output = "Traceroute to $host from Mikrotik...\n";
        $this->output .= "Hop | Address         | Loss | Sent | Last | Avg\n";
        $this->output .= "------------------------------------------------------------\n";
        
        $service = app(MikrotikService::class);
        $isSimulated = $service->isSimulationMode();
        
        if ($isSimulated) {
            $this->output = "[SIMULASI - TIDAK ADA KONEKSI ROUTER]\n" . $this->output;
        }
        
        $results = $service->traceroute($host);

        if (isset($results[0]['status']) && $results[0]['status'] === 'error') {
            $this->output .= "Error: " . $results[0]['message'];
            return;
        }

        foreach ($results as $res) {
            $hop = $res['hop'] ?? '?';
            $address = $res['address'] ?? 'timeout';
            $loss = $res['loss'] ?? '0';
            $last = $res['last'] ?? '0';
            $avg = $res['avg'] ?? '0';
            
            $this->output .= sprintf("%-3s | %-15s | %-4s | %-4s | %-4s | %-4s\n", 
                $hop, $address, $loss, $res['sent'] ?? '1', $last, $avg);
        }
    }

    public function setupRedirect(): void
    {
        $serverIp = $this->data['server_ip'] ?? request()->server('SERVER_ADDR') ?? $_SERVER['SERVER_ADDR'] ?? '10.62.38.208';
        
        $service = app(MikrotikService::class);
        $isSimulated = $service->isSimulationMode();
        $success = $service->setupIsolationNAT($serverIp);

        if ($success) {
            Notification::make()
                ->title($isSimulated ? 'Redirect Berhasil (Simulasi)' : 'Redirect Berhasil Dipasang')
                ->body("Traffic HTTP (Port 80) dari pelanggan ISOLATED sekarang diarahkan ke $serverIp.")
                ->success()
                ->send();
            
            $this->output = ($isSimulated ? "[SIMULASI - TIDAK ADA KONEKSI ROUTER]\n" : "") . 
                "NAT Redirect Rule installed successfully to $serverIp:80\n";
        } else {
            Notification::make()->title('Gagal pasang NAT Redirect!')->danger()->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ping')
                ->label('Run Ping')
                ->color('info')
                ->icon('heroicon-o-signal')
                ->action('runPing'),
            
            Action::make('traceroute')
                ->label('Traceroute')
                ->color('warning')
                ->icon('heroicon-o-arrows-up-down')
                ->action('runTraceroute'),

            Action::make('setup_redirect')
                ->label('Setup Redirect')
                ->color('danger')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Pasang NAT Redirect?')
                ->modalDescription('Ini akan otomatis memasang rule NAT di Mikrotik untuk mengalihkan pelanggan yang diisolir ke halaman billing.')
                ->action('setupRedirect'),

            Action::make('execute')
                ->label('Run Command')
                ->color('success')
                ->icon('heroicon-o-play')
                ->action('runCommand'),
            
            Action::make('clear')
                ->label('Clear Terminal')
                ->color('gray')
                ->action(fn () => $this->output = ''),
        ];
    }
}
