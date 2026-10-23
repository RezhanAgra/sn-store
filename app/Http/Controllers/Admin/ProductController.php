<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index(): View
    {
        return view('pages.admin.products.index', ['products' => $this->products()]);
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create(): View
    {
        return view('pages.admin.products.create');
    }

    /**
     * Data dummy produk. Ganti dengan Product::latest()->get() saat model tersedia.
     *
     * @return array<int, array{name: string, category: string, price: int, stock: int, image: string}>
     */
    private function products(): array
    {
        $items = [
            ['Smartphone Nova X12 256GB', 'Smartphone', 6499000, 24],
            ['Laptop Zenith Pro 14" i7', 'Laptop', 15999000, 8],
            ['Headphone Wireless Aura ANC', 'Audio', 1299000, 56],
            ['Kamera Mirrorless Lumo M5', 'Kamera', 11499000, 0],
            ['Smartwatch Pulse S3', 'Smartwatch', 1899000, 31],
            ['Speaker Bluetooth Boom 360', 'Audio', 749000, 42],
            ['Tablet Slate 11" 128GB', 'Tablet', 4299000, 0],
            ['Power Bank Volt 20.000 mAh', 'Aksesoris', 359000, 120],
            ['Keyboard Mekanikal Strike TKL', 'Aksesoris', 899000, 17],
            ['Monitor Vista 27" QHD 144Hz', 'Monitor', 3799000, 9],
        ];

        return array_map(fn (array $item, int $index): array => [
            'name' => $item[0],
            'category' => $item[1],
            'price' => $item[2],
            'stock' => $item[3],
            'image' => 'https://picsum.photos/seed/snstore-product'.($index + 1).'/120/120',
        ], $items, array_keys($items));
    }
}
