<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', Order::count())
                ->description('Semua pesanan yang masuk')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Stat::make('Estimasi Omzet', 'Rp ' . number_format(Order::where('status', 'completed')->sum('total_price'), 0, ',', '.'))
                ->description('Dari pesanan yang telah selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Paket Aktif', Package::where('status', 'active')->count())
                ->description('Penawaran yang saat ini tayang')
                ->descriptionIcon('heroicon-m-gift')
                ->color('warning'),
            Stat::make('Pelanggan Baru', User::where('role', 'customer')->count())
                ->description('Basis pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
