<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin.
     */
    public function index(): View
    {
        return view('pages.admin.dashboard.index', [
            'stats' => $this->stats(),
            'weeklyRevenue' => $this->weeklyRevenue(),
            'recentTransactions' => $this->recentTransactions(),
            'topProducts' => $this->topProducts(),
        ]);
    }

    /**
     * Data dummy ringkasan. Ganti dengan agregasi dari database saat model tersedia.
     *
     * @return array<int, array{label: string, value: string, change: float, icon: string}>
     */
    private function stats(): array
    {
        return [
            ['label' => 'Total Pendapatan', 'value' => 'Rp 128,4 jt', 'change' => 12.5, 'icon' => 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['label' => 'Total Transaksi', 'value' => '1.248', 'change' => 8.2, 'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z'],
            ['label' => 'Total Produk', 'value' => '342', 'change' => 3.1, 'icon' => 'm21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
            ['label' => 'Pelanggan Baru', 'value' => '86', 'change' => -2.4, 'icon' => 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z'],
        ];
    }

    /**
     * @return array<int, array{day: string, amount: int}>
     */
    private function weeklyRevenue(): array
    {
        $amounts = ['Sen' => 12400000, 'Sel' => 18900000, 'Rab' => 15200000, 'Kam' => 22700000, 'Jum' => 26100000, 'Sab' => 31800000, 'Min' => 19600000];

        return array_map(
            fn (string $day, int $amount): array => ['day' => $day, 'amount' => $amount],
            array_keys($amounts),
            $amounts,
        );
    }

    /**
     * @return array<int, array{code: string, customer: string, total: int, status: string, date: string}>
     */
    private function recentTransactions(): array
    {
        return [
            ['code' => 'INV-2410-0128', 'customer' => 'Budi Santoso', 'total' => 6499000, 'status' => 'paid', 'date' => '23 Okt 2026'],
            ['code' => 'INV-2410-0127', 'customer' => 'Siti Rahma', 'total' => 1299000, 'status' => 'shipped', 'date' => '23 Okt 2026'],
            ['code' => 'INV-2410-0126', 'customer' => 'Andi Pratama', 'total' => 15999000, 'status' => 'pending', 'date' => '22 Okt 2026'],
            ['code' => 'INV-2410-0125', 'customer' => 'Dewi Lestari', 'total' => 749000, 'status' => 'paid', 'date' => '22 Okt 2026'],
            ['code' => 'INV-2410-0124', 'customer' => 'Rizky Maulana', 'total' => 3799000, 'status' => 'cancelled', 'date' => '21 Okt 2026'],
        ];
    }

    /**
     * @return array<int, array{name: string, category: string, sold: int, image: string}>
     */
    private function topProducts(): array
    {
        return [
            ['name' => 'Smartphone Nova X12 256GB', 'category' => 'Smartphone', 'sold' => 184, 'image' => 'https://picsum.photos/seed/snstore-product1/120/120'],
            ['name' => 'Headphone Wireless Aura ANC', 'category' => 'Audio', 'sold' => 152, 'image' => 'https://picsum.photos/seed/snstore-product3/120/120'],
            ['name' => 'Smartwatch Pulse S3', 'category' => 'Smartwatch', 'sold' => 117, 'image' => 'https://picsum.photos/seed/snstore-product5/120/120'],
            ['name' => 'Power Bank Volt 20.000 mAh', 'category' => 'Aksesoris', 'sold' => 96, 'image' => 'https://picsum.photos/seed/snstore-product8/120/120'],
        ];
    }
}
