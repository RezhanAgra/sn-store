<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi.
     */
    public function index(): View
    {
        return view('pages.admin.transactions.index', ['transactions' => $this->transactions()]);
    }

    /**
     * Tampilkan form tambah transaksi.
     */
    public function create(): View
    {
        return view('pages.admin.transactions.create', [
            'statuses' => ['pending' => 'Menunggu', 'paid' => 'Dibayar', 'shipped' => 'Dikirim', 'cancelled' => 'Dibatalkan'],
        ]);
    }

    /**
     * Data dummy transaksi. Ganti dengan Transaction::latest()->get() saat model tersedia.
     *
     * @return array<int, array{code: string, customer: string, total: int, status: string, date: string}>
     */
    private function transactions(): array
    {
        $items = [
            ['Budi Santoso', 6499000, 'paid', '23 Okt 2026'],
            ['Siti Rahma', 1299000, 'shipped', '23 Okt 2026'],
            ['Andi Pratama', 15999000, 'pending', '22 Okt 2026'],
            ['Dewi Lestari', 749000, 'paid', '22 Okt 2026'],
            ['Rizky Maulana', 3799000, 'cancelled', '21 Okt 2026'],
            ['Maya Putri', 1899000, 'paid', '21 Okt 2026'],
            ['Fajar Nugroho', 359000, 'shipped', '20 Okt 2026'],
            ['Lina Marlina', 4299000, 'pending', '20 Okt 2026'],
            ['Hendra Wijaya', 899000, 'paid', '19 Okt 2026'],
            ['Nadia Safitri', 11499000, 'shipped', '19 Okt 2026'],
        ];

        return array_map(fn (array $item, int $index): array => [
            'code' => 'INV-2410-'.str_pad((string) (128 - $index), 4, '0', STR_PAD_LEFT),
            'customer' => $item[0],
            'total' => $item[1],
            'status' => $item[2],
            'date' => $item[3],
        ], $items, array_keys($items));
    }
}
