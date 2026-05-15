@php
    $packageCount = \App\Models\InternetPackage::count();
    $activeCount = \App\Models\Customer::where('status', 'active')->count();
    $unpaidCount = \App\Models\Invoice::where('status', 'unpaid')->count();
    $isolatedCount = \App\Models\Customer::where('status', 'isolated')->count();
@endphp

<x-filament-widgets::widget>
    <div class="nb-sidebar-widget grid grid-cols-1 lg:grid-cols-2 gap-6" style="font-family: 'Inter', sans-serif;">
    {{-- Aksi Cepat --}}
    <div class="nb-card-adaptive" style="border-radius: 20px; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
        <div class="nb-card-header" style="padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--nb-divider);">
            <div style="padding: 6px; background: linear-gradient(135deg, #a855f7, #6366f1); border-radius: 8px;">
                <x-heroicon-m-bolt style="width: 16px; height: 16px; color: white;" />
            </div>
            <span class="nb-header-label" style="font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--nb-text-primary);">Aksi Cepat</span>
        </div>
        
        <div style="padding: 24px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; flex-grow: 1;">
            <a href="/admin/customers/create" class="nb-action-card group relative overflow-hidden" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-radius: 16px; text-decoration: none; border: 1px solid var(--nb-divider); background: var(--nb-bg-deep);">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div style="padding: 12px; border-radius: 14px; background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(99, 102, 241, 0.1)); box-shadow: inset 0 0 0 1px rgba(168, 85, 247, 0.2);">
                    <x-heroicon-o-user-plus style="width: 28px; height: 28px; color: #a855f7;" />
                </div>
                <span style="font-size: 13px; font-weight: 700; color: var(--nb-text-primary);">Pelanggan Baru</span>
            </a>
            
            <a href="/admin/invoices/create" class="nb-action-card group relative overflow-hidden" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-radius: 16px; text-decoration: none; border: 1px solid var(--nb-divider); background: var(--nb-bg-deep);">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div style="padding: 12px; border-radius: 14px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(6, 182, 212, 0.1)); box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.2);">
                    <x-heroicon-o-document-plus style="width: 28px; height: 28px; color: #3b82f6;" />
                </div>
                <span style="font-size: 13px; font-weight: 700; color: var(--nb-text-primary);">Buat Invoice</span>
            </a>

            <a href="/admin/payments/create" class="nb-action-card group relative overflow-hidden" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-radius: 16px; text-decoration: none; border: 1px solid var(--nb-divider); background: var(--nb-bg-deep);">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div style="padding: 12px; border-radius: 14px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(20, 184, 166, 0.1)); box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.2);">
                    <x-heroicon-o-banknotes style="width: 28px; height: 28px; color: #10b981;" />
                </div>
                <span style="font-size: 13px; font-weight: 700; color: var(--nb-text-primary);">Terima Bayar</span>
            </a>

            <a href="/admin/internet-packages" class="nb-action-card group relative overflow-hidden" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-radius: 16px; text-decoration: none; border: 1px solid var(--nb-divider); background: var(--nb-bg-deep);">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div style="padding: 12px; border-radius: 14px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(249, 115, 22, 0.1)); box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.2);">
                    <x-heroicon-o-signal style="width: 28px; height: 28px; color: #f59e0b;" />
                </div>
                <span style="font-size: 13px; font-weight: 700; color: var(--nb-text-primary);">Kelola Paket</span>
            </a>
        </div>
    </div>

    {{-- Status Jaringan (New) --}}
    <div class="nb-card-adaptive" style="border-radius: 20px; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
        <div class="nb-card-header" style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--nb-divider);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="padding: 6px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 8px;">
                    <x-heroicon-m-server style="width: 16px; height: 16px; color: white;" />
                </div>
                <span class="nb-header-label" style="font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--nb-text-primary);">Status Server</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: rgba(16, 185, 129, 0.1); border-radius: 20px; border: 1px solid rgba(16, 185, 129, 0.2);">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #10b981; animation: nb-pulse 2s infinite;"></span>
                <span style="font-size: 11px; font-weight: 800; color: #10b981; letter-spacing: 0.05em;">OPTIMAL</span>
            </div>
        </div>
        
        <div style="padding: 24px; display: flex; flex-direction: column; gap: 20px; flex-grow: 1; justify-content: space-between;">
            {{-- CPU & RAM --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: var(--nb-bg-deep); padding: 20px; border-radius: 16px; border: 1px solid var(--nb-divider); box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px;">
                        <span style="font-size: 12px; font-weight: 700; color: var(--nb-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">CPU Load</span>
                        <span style="font-size: 24px; font-weight: 900; color: var(--nb-text-primary); line-height: 1;">12%</span>
                    </div>
                    <div style="height: 6px; background: var(--nb-divider); border-radius: 4px; overflow: hidden;">
                        <div style="width: 12%; height: 100%; background: linear-gradient(90deg, #a855f7, #c084fc); border-radius: 4px; box-shadow: 0 0 10px rgba(168, 85, 247, 0.5);"></div>
                    </div>
                </div>
                
                <div style="background: var(--nb-bg-deep); padding: 20px; border-radius: 16px; border: 1px solid var(--nb-divider); box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px;">
                        <span style="font-size: 12px; font-weight: 700; color: var(--nb-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Memory</span>
                        <span style="font-size: 24px; font-weight: 900; color: var(--nb-text-primary); line-height: 1;">42%</span>
                    </div>
                    <div style="height: 6px; background: var(--nb-divider); border-radius: 4px; overflow: hidden;">
                        <div style="width: 42%; height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 4px; box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);"></div>
                    </div>
                </div>
            </div>
            
            {{-- Network Traffic --}}
            <div style="background: var(--nb-bg-deep); padding: 20px; border-radius: 16px; border: 1px solid var(--nb-divider); display: flex; justify-content: space-between; align-items: center; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);">
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="padding: 10px; border-radius: 12px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.15)); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <x-heroicon-m-arrow-down-tray style="width: 20px; height: 20px; color: #10b981;" />
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--nb-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Download</div>
                        <div style="font-size: 16px; font-weight: 800; color: var(--nb-text-primary); margin-top: 2px;">1.2 Gbps</div>
                    </div>
                </div>
                
                <div style="width: 2px; height: 40px; background: var(--nb-divider); border-radius: 1px;"></div>
                
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="padding: 10px; border-radius: 12px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.15)); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <x-heroicon-m-arrow-up-tray style="width: 20px; height: 20px; color: #f59e0b;" />
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--nb-text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Upload</div>
                        <div style="font-size: 16px; font-weight: 800; color: var(--nb-text-primary); margin-top: 2px;">450 Mbps</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>

