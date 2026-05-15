<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit.prevent="runCommand">
            {{ $this->form }}
        </form>

        <div class="terminal-container" style="background: #0d0d0d; border: 1px solid #333; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div class="terminal-header" style="background: #1a1a1a; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #333;">
                <div style="display: flex; gap: 8px;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #ff5f56;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #ffbd2e;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #27c93f;"></div>
                </div>
                <span style="font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.1em;">VeloNet Mikrotik Shell v1.0</span>
            </div>
            
            <div class="terminal-body" style="padding: 24px; min-height: 400px; max-height: 600px; overflow-y: auto;">
                <pre style="font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 14px; line-height: 1.6; color: #00ff00; white-space: pre-wrap;">{{ $output ?: 'Waiting for command...' }}</pre>
            </div>
        </div>
    </div>

    <style>
        .terminal-body::-webkit-scrollbar {
            width: 8px;
        }
        .terminal-body::-webkit-scrollbar-track {
            background: #0d0d0d;
        }
        .terminal-body::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }
        .terminal-body::-webkit-scrollbar-thumb:hover {
            background: #444;
        }
    </style>
</x-filament-panels::page>
