<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda.
     */
    public function index(): View
    {
        return view('pages.public.home', [
            'slides' => config('storefront.slides'),
            'products' => $this->latestProducts(),
        ]);
    }

    /**
     * Data dummy 10 produk terbaru. Ganti dengan query Product::latest()->take(10).
     *
     * @return array<int, array{name: string, category: string, price: int, old_price: int|null, rating: float, image: string, slug: string}>
     */
    private function latestProducts(): array
    {
        $items = [
            ['Smartphone Nova X12 256GB', 'Smartphone', 6499000, 7299000],
            ['Laptop Zenith Pro 14" i7', 'Laptop', 15999000, null],
            ['Headphone Wireless Aura ANC', 'Audio', 1299000, 1599000],
            ['Kamera Mirrorless Lumo M5', 'Kamera', 11499000, null],
            ['Smartwatch Pulse S3', 'Smartwatch', 1899000, 2199000],
            ['Speaker Bluetooth Boom 360', 'Audio', 749000, null],
            ['Tablet Slate 11" 128GB', 'Tablet', 4299000, 4799000],
            ['Power Bank Volt 20.000 mAh', 'Aksesoris', 359000, 429000],
            ['Keyboard Mekanikal Strike TKL', 'Aksesoris', 899000, null],
            ['Monitor Vista 27" QHD 144Hz', 'Monitor', 3799000, 4299000],
        ];

        return array_map(fn (array $item, int $index): array => [
            'name' => $item[0],
            'category' => $item[1],
            'price' => $item[2],
            'old_price' => $item[3],
            'rating' => 4.3 + (($index % 7) / 10),
            'image' => 'https://picsum.photos/seed/snstore-product'.($index + 1).'/600/600',
            'slug' => str($item[0])->slug()->toString(),
        ], $items, array_keys($items));
    }
}
