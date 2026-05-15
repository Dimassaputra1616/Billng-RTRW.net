<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $currentMonthRevenue = \App\Models\Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        $lastMonthRevenue = \App\Models\Payment::whereMonth('payment_date', now()->subMonth()->month)
            ->whereYear('payment_date', now()->subMonth()->year)
            ->sum('amount_paid');

        $revenueChange = $lastMonthRevenue > 0
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $activeCustomers = \App\Models\Customer::where('status', 'active')->count();
        $isolatedCustomers = \App\Models\Customer::where('status', 'isolated')->count();
        $unpaidInvoices = \App\Models\Invoice::where('status', 'unpaid')->count();
        $overdueInvoices = \App\Models\Invoice::where('status', 'overdue')->count();

        return [
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($currentMonthRevenue, 0, ',', '.'))
                ->description($revenueChange >= 0 ? "+{$revenueChange}%" : "{$revenueChange}%")
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3, 7, 8, 9, 12, 14, 15]),
            Stat::make('Pelanggan Aktif', (string) $activeCustomers)
                ->description('Total terdaftar aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([1, 2, 2, 3, 4, 4, 5, 6, 7, 8, 9, 10]),
            Stat::make('Tagihan Belum Bayar', (string) $unpaidInvoices)
                ->description($overdueInvoices > 0 ? "{$overdueInvoices} terlambat" : 'Semua lancar')
                ->descriptionIcon($overdueInvoices > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($unpaidInvoices > 0 ? 'warning' : 'success')
                ->chart([10, 8, 6, 5, 4, 2, 1, 0]),
            Stat::make('Pelanggan Terisolir', (string) $isolatedCustomers)
                ->description($isolatedCustomers > 0 ? 'Perlu pengecekan' : 'Tidak ada')
                ->descriptionIcon($isolatedCustomers > 0 ? 'heroicon-m-no-symbol' : 'heroicon-m-check-circle')
                ->color($isolatedCustomers > 0 ? 'danger' : 'success')
                ->chart([0, 0, 0, 0, 1, 0, 0, 1]),
        ];
    }
}
