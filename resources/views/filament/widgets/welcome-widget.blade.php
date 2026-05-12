<div style="position: relative; background: rgba(18, 10, 26, 0.6); border: 1px solid rgba(168, 85, 247, 0.2); border-radius: 16px; padding: 20px; overflow: hidden; backdrop-filter: blur(15px); margin-bottom: 20px; font-family: 'Inter', sans-serif;">
    {{-- Glow Accents --}}
    <div style="position: absolute; right: -40px; top: -40px; width: 128px; height: 128px; border-radius: 50%; background: rgba(168, 85, 247, 0.15); filter: blur(40px);"></div>
    <div style="position: absolute; left: -40px; bottom: -40px; width: 128px; height: 128px; border-radius: 50%; background: rgba(59, 130, 246, 0.1); filter: blur(40px);"></div>

    <div style="position: relative; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #a855f7; box-shadow: 0 0 10px #a855f7; animation: pulse 2s infinite;"></div>
                <span style="font-size: 10px; font-weight: 900; color: #a855f7; text-transform: uppercase; letter-spacing: 0.2em;">System Active</span>
            </div>
            
            <h1 style="font-size: 24px; font-weight: 900; color: #ffffff; letter-spacing: -0.02em; margin: 0;">
                @php
                    $hour = now()->hour;
                    $greeting = match(true) {
                        $hour < 5 => 'Selamat Malam',
                        $hour < 12 => 'Selamat Pagi',
                        $hour < 15 => 'Selamat Siang',
                        $hour < 18 => 'Selamat Sore',
                        default => 'Selamat Malam',
                    };
                @endphp
                {{ $greeting }}, <span style="background: linear-gradient(to right, #d8b4fe, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ auth()->user()->name }}</span> 👋
            </h1>
            
            <p style="margin: 12px 0 0 0; font-size: 14px; font-weight: 500; color: #94a3b8;">
                <x-heroicon-m-calendar style="display: inline-block; width: 16px; height: 16px; vertical-align: middle; margin-right: 4px; color: #a855f7;" />
                {{ now()->translatedFormat('l, d F Y') }} — <span style="color: rgba(168, 85, 247, 0.6);">Monitoring Billing Pro</span>
            </p>
        </div>

        <div style="display: flex; align-items: center; gap: 24px;">
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Server Load</div>
                <div style="font-size: 14px; font-weight: 900; color: #10b981;">OPTIMAL</div>
            </div>
            <div style="width: 1px; height: 40px; background: rgba(255, 255, 255, 0.05);"></div>
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Pending</div>
                <div style="font-size: 14px; font-weight: 900; color: #ffffff;">{{ \App\Models\Invoice::where('status', 'unpaid')->count() }} Tagihan</div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
