@php
    $packageCount = \App\Models\InternetPackage::count();
    $activeCount = \App\Models\Customer::where('status', 'active')->count();
    $unpaidCount = \App\Models\Invoice::where('status', 'unpaid')->count();
    $isolatedCount = \App\Models\Customer::where('status', 'isolated')->count();
@endphp

<x-filament-widgets::widget>
    <div class="nb-sidebar-widget grid grid-cols-1 lg:grid-cols-2 gap-6" style="font-family: 'Inter', sans-serif;">
    {{-- Aksi Cepat --}}
    <div class="nb-card-adaptive" style="border-radius: 16px; overflow: hidden; height: 100%;">
        <div class="nb-card-header" style="padding: 16px 20px; display: flex; align-items: center; gap: 10px;">
            <x-heroicon-m-bolt style="width: 18px; height: 18px;" class="nb-icon-accent" />
            <span class="nb-header-label" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">Aksi Cepat</span>
        </div>
        
        <div style="padding: 20px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; height: calc(100% - 50px);">
            <a href="/admin/customers/create" class="nb-action-card nb-action-purple" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px; border-radius: 14px; text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(168, 85, 247, 0.15); background: rgba(168, 85, 247, 0.04);">
                <div class="nb-icon-circle" style="padding: 10px; border-radius: 12px; background: rgba(168, 85, 247, 0.1);">
                    <x-heroicon-o-user-plus style="width: 24px; height: 24px; color: #a855f7;" />
                </div>
                <span class="nb-action-label" style="font-size: 12px; font-weight: 700; color: var(--nb-text-primary);">Pelanggan</span>
            </a>
            
            <a href="/admin/invoices/create" class="nb-action-card nb-action-blue" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px; border-radius: 14px; text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(59, 130, 246, 0.15); background: rgba(59, 130, 246, 0.04);">
                <div class="nb-icon-circle" style="padding: 10px; border-radius: 12px; background: rgba(59, 130, 246, 0.1);">
                    <x-heroicon-o-document-plus style="width: 24px; height: 24px; color: #3b82f6;" />
                </div>
                <span class="nb-action-label" style="font-size: 12px; font-weight: 700; color: var(--nb-text-primary);">Invoice</span>
            </a>

            <a href="/admin/payments/create" class="nb-action-card nb-action-green" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px; border-radius: 14px; text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(16, 185, 129, 0.15); background: rgba(16, 185, 129, 0.04);">
                <div class="nb-icon-circle" style="padding: 10px; border-radius: 12px; background: rgba(16, 185, 129, 0.1);">
                    <x-heroicon-o-banknotes style="width: 24px; height: 24px; color: #10b981;" />
                </div>
                <span class="nb-action-label" style="font-size: 12px; font-weight: 700; color: var(--nb-text-primary);">Bayar</span>
            </a>

            <a href="/admin/internet-packages" class="nb-action-card nb-action-amber" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px; border-radius: 14px; text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(245, 158, 11, 0.15); background: rgba(245, 158, 11, 0.04);">
                <div class="nb-icon-circle" style="padding: 10px; border-radius: 12px; background: rgba(245, 158, 11, 0.1);">
                    <x-heroicon-o-signal style="width: 24px; height: 24px; color: #f59e0b;" />
                </div>
                <span class="nb-action-label" style="font-size: 12px; font-weight: 700; color: var(--nb-text-primary);">Paket</span>
            </a>
        </div>
    </div>

    {{-- Status Jaringan (New) --}}
    <div class="nb-card-adaptive" style="border-radius: 16px; overflow: hidden; height: 100%;">
        <div class="nb-card-header" style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <x-heroicon-m-server style="width: 18px; height: 18px; color: #10b981;" />
                <span class="nb-header-label" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">Status Server</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);"></span>
                <span style="font-size: 10px; font-weight: 700; color: #10b981;">ONLINE</span>
            </div>
        </div>
        
        <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px; justify-content: center; height: calc(100% - 50px);">
            {{-- CPU & RAM --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="background: var(--nb-bg-deep); padding: 16px; border-radius: 12px; border: 1px solid var(--nb-border-accent);">
                    <div style="font-size: 11px; font-weight: 600; color: var(--nb-text-secondary); margin-bottom: 8px; text-transform: uppercase;">CPU Load</div>
                    <div style="font-size: 20px; font-weight: 800; color: var(--nb-text-primary);">12%</div>
                    <div style="margin-top: 8px; height: 4px; background: rgba(168, 85, 247, 0.1); border-radius: 2px; overflow: hidden;">
                        <div style="width: 12%; height: 100%; background: #a855f7; border-radius: 2px;"></div>
                    </div>
                </div>
                <div style="background: var(--nb-bg-deep); padding: 16px; border-radius: 12px; border: 1px solid var(--nb-border-accent);">
                    <div style="font-size: 11px; font-weight: 600; color: var(--nb-text-secondary); margin-bottom: 8px; text-transform: uppercase;">Memory</div>
                    <div style="font-size: 20px; font-weight: 800; color: var(--nb-text-primary);">42%</div>
                    <div style="margin-top: 8px; height: 4px; background: rgba(59, 130, 246, 0.1); border-radius: 2px; overflow: hidden;">
                        <div style="width: 42%; height: 100%; background: #3b82f6; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
            
            {{-- Network Traffic --}}
            <div style="background: var(--nb-bg-deep); padding: 16px; border-radius: 12px; border: 1px solid var(--nb-border-accent); display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="padding: 8px; border-radius: 8px; background: rgba(16, 185, 129, 0.1);">
                        <x-heroicon-o-arrow-down style="width: 16px; height: 16px; color: #10b981;" />
                    </div>
                    <div>
                        <div style="font-size: 10px; font-weight: 600; color: var(--nb-text-secondary); text-transform: uppercase;">Download</div>
                        <div style="font-size: 14px; font-weight: 700; color: var(--nb-text-primary);">1.2 Gbps</div>
                    </div>
                </div>
                
                <div style="width: 1px; height: 30px; background: var(--nb-border-accent);"></div>
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="padding: 8px; border-radius: 8px; background: rgba(245, 158, 11, 0.1);">
                        <x-heroicon-o-arrow-up style="width: 16px; height: 16px; color: #f59e0b;" />
                    </div>
                    <div>
                        <div style="font-size: 10px; font-weight: 600; color: var(--nb-text-secondary); text-transform: uppercase;">Upload</div>
                        <div style="font-size: 14px; font-weight: 700; color: var(--nb-text-primary);">450 Mbps</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>

