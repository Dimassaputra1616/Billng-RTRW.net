@php
    $unpaidCount = \App\Models\Invoice::where('status', 'unpaid')->count();
@endphp

<div style="display: flex; align-items: center; gap: 8px; padding: 0 8px;">
    {{-- Server Load Badge --}}
    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1; white-space: nowrap; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981;">
        <div style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; animation: nb-pulse 2s infinite;"></div>
        <span>Optimal</span>
    </div>

    {{-- Pending Invoices Badge --}}
    <a href="/admin/invoices" style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1; white-space: nowrap; text-decoration: none; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25); color: #f59e0b; transition: transform 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 12px; height: 12px;">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
        </svg>
        <span>{{ $unpaidCount }} Pending</span>
    </a>
</div>
