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

<div class="nb-welcome-card" style="position: relative; border-radius: 16px; padding: 24px; overflow: hidden; margin-bottom: 20px; font-family: 'Inter', sans-serif;">
    {{-- Glow Accents --}}
    <div class="nb-glow-1" style="position: absolute; right: -40px; top: -40px; width: 128px; height: 128px; border-radius: 50%; filter: blur(40px);"></div>
    <div class="nb-glow-2" style="position: absolute; left: -40px; bottom: -40px; width: 128px; height: 128px; border-radius: 50%; filter: blur(40px);"></div>

    <div style="position: relative; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                <div class="nb-pulse-dot" style="width: 8px; height: 8px; border-radius: 50%; animation: nb-pulse 2s infinite;"></div>
                <span class="nb-status-label" style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em;">System Active</span>
            </div>
            
            <h1 class="nb-greeting" style="font-size: 28px; font-weight: 900; letter-spacing: -0.02em; margin: 0;">
                {{ $greeting }}, <span class="nb-name-gradient">{{ auth()->user()->name }}</span> 👋
            </h1>
            
            <p class="nb-date-text" style="margin: 16px 0 0 0; font-size: 14px; font-weight: 500;">
                <x-heroicon-m-calendar class="nb-icon-accent" style="display: inline-block; width: 18px; height: 18px; vertical-align: middle; margin-right: 6px;" />
                {{ now()->translatedFormat('l, d F Y') }} — <span class="nb-billing-label">VeloNet Monitoring</span>
            </p>
        </div>
    </div>
</div>

<style>
@keyframes nb-pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
