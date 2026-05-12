<div style="display: flex; flex-direction: column; gap: 24px; font-family: 'Inter', sans-serif;">
    {{-- Aksi Cepat --}}
    <div style="background: rgba(18, 10, 26, 0.6); border: 1px solid rgba(168, 85, 247, 0.15); border-radius: 16px; overflow: hidden; backdrop-filter: blur(10px);">
        <div style="padding: 16px 20px; border-bottom: 1px solid rgba(168, 85, 247, 0.1); display: flex; align-items: center; gap: 10px;">
            <x-heroicon-m-bolt style="width: 18px; height: 18px; color: #a855f7;" />
            <span style="font-size: 11px; font-weight: 800; color: #d8b4fe; text-transform: uppercase; letter-spacing: 0.1em;">Aksi Cepat</span>
        </div>
        
        <div style="padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <a href="/admin/customers/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: rgba(168, 85, 247, 0.05); border: 1px solid rgba(168, 85, 247, 0.1); border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                <x-heroicon-o-user-plus style="width: 24px; height: 24px; color: #a855f7;" />
                <span style="font-size: 11px; font-weight: 600; color: #ffffff;">Pelanggan</span>
            </a>
            
            <a href="/admin/invoices/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.1); border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                <x-heroicon-o-document-plus style="width: 24px; height: 24px; color: #3b82f6;" />
                <span style="font-size: 11px; font-weight: 600; color: #ffffff;">Invoice</span>
            </a>

            <a href="/admin/payments/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.1); border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                <x-heroicon-o-banknotes style="width: 24px; height: 24px; color: #10b981;" />
                <span style="font-size: 11px; font-weight: 600; color: #ffffff;">Bayar</span>
            </a>

            <a href="/admin/internet-packages" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.1); border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                <x-heroicon-o-signal style="width: 24px; height: 24px; color: #f59e0b;" />
                <span style="font-size: 11px; font-weight: 600; color: #ffffff;">Paket</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div style="background: rgba(18, 10, 26, 0.6); border: 1px solid rgba(168, 85, 247, 0.15); border-radius: 16px; overflow: hidden; backdrop-filter: blur(10px);">
        <div style="padding: 16px 20px; border-bottom: 1px solid rgba(168, 85, 247, 0.1); display: flex; align-items: center; gap: 10px;">
            <x-heroicon-m-presentation-chart-line style="width: 18px; height: 18px; color: #a855f7;" />
            <span style="font-size: 11px; font-weight: 800; color: #d8b4fe; text-transform: uppercase; letter-spacing: 0.1em;">Ringkasan</span>
        </div>
        
        <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #a855f7; box-shadow: 0 0 8px #a855f7;"></div>
                    <span style="font-size: 12px; color: #94a3b8;">Total Paket</span>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #ffffff;">{{ \App\Models\InternetPackage::count() }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></div>
                    <span style="font-size: 12px; color: #94a3b8;">Pelanggan Aktif</span>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #10b981;">{{ \App\Models\Customer::where('status', 'active')->count() }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></div>
                    <span style="font-size: 12px; color: #94a3b8;">Belum Bayar</span>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #f59e0b;">{{ \App\Models\Invoice::where('status', 'unpaid')->count() }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #f43f5e; box-shadow: 0 0 8px #f43f5e;"></div>
                    <span style="font-size: 12px; color: #94a3b8;">Terisolir</span>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #f43f5e;">{{ \App\Models\Customer::where('status', 'isolated')->count() }}</span>
            </div>
        </div>
    </div>
</div>
