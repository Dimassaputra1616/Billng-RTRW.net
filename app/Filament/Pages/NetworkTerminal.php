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
                    Grid::make(2)
                        ->schema([
                            TextInput::make('ping_host')
                                ->label('Ping Host / IP')
                                ->placeholder('8.8.8.8')
                                ->required()
                                ->hint('Tekan tombol Ping untuk mengetes koneksi.'),
                            
                            TextInput::make('raw_command')
                                ->label('Raw Command (API Path)')
                                ->placeholder('/ip/address/print')
                                ->hint('Masukkan path API Mikrotik.'),
                        ]),
                ])->statePath('data'),
        ];
    }

    public function runPing(): void
    {
        $host = $this->data['ping_host'] ?? null;
        
        if (!$host) {
            Notification::make()->title('Masukkan IP/Host dulu Bang!')->danger()->send();
            return;
        }

        $this->output = "Pinging $host from Mikrotik...\n";
        
        $service = app(MikrotikService::class);
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
        $cmd = $this->data['raw_command'] ?? null;

        if (!$cmd) {
            Notification::make()->title('Masukkan Perintah API dulu Bang!')->danger()->send();
            return;
        }

        $this->output = "Executing $cmd...\n";

        $service = app(MikrotikService::class);
        $results = $service->executeRaw($cmd);

        $this->output .= json_encode($results, JSON_PRETTY_PRINT);
    }

    public function runTraceroute(): void
    {
        $host = $this->data['ping_host'] ?? null;
        
        if (!$host) {
            Notification::make()->title('Masukkan IP/Host dulu Bang!')->danger()->send();
            return;
        }

        $this->output = "Traceroute to $host from Mikrotik...\n";
        $this->output .= "Hop | Address | Loss | Sent | Last | Avg | Best | Worst\n";
        $this->output .= "------------------------------------------------------------\n";
        
        $service = app(MikrotikService::class);
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
        // Ambil IP server dari request (IP lokal server)
        $serverIp = request()->server('SERVER_ADDR') ?? $_SERVER['SERVER_ADDR'] ?? '192.168.1.2';
        
        $service = app(MikrotikService::class);
        $success = $service->setupIsolationNAT($serverIp);

        if ($success) {
            Notification::make()
                ->title('Redirect Berhasil Dipasang')
                ->body("Traffic HTTP (Port 80) dari pelanggan ISOLATED sekarang diarahkan ke $serverIp.")
                ->success()
                ->send();
            
            $this->output .= "NAT Redirect Rule installed successfully to $serverIp:80\n";
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
