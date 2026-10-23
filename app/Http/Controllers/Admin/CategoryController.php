<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori.
     */
    public function index(): View
    {
        return view('pages.admin.categories.index', ['categories' => $this->categories()]);
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create(): View
    {
        return view('pages.admin.categories.create');
    }

    /**
     * Data dummy kategori. Ganti dengan Category::latest()->get() saat model tersedia.
     *
     * @return array<int, array{name: string, slug: string, description: string, status: string, created_at: string}>
     */
    private function categories(): array
    {
        $items = [
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'description' => 'Power bank, kabel, keyboard, dan aksesoris lainnya.'],
            ['name' => 'Smartwatch', 'slug' => 'smartwatch', 'description' => 'Jam tangan pintar dan fitness tracker.'],
            ['name' => 'Kamera', 'slug' => 'kamera', 'description' => 'Kamera mirrorless, DSLR, dan aksesorisnya.'],
            ['name' => 'Audio & Headphone', 'slug' => 'audio', 'description' => 'Headphone, earphone, dan speaker berkualitas.'],
            ['name' => 'Laptop', 'slug' => 'laptop', 'description' => 'Laptop untuk kerja, kuliah, dan gaming.'],
            ['name' => 'Smartphone', 'slug' => 'smartphone', 'description' => 'Smartphone Android dan iOS dari berbagai merek.'],
        ];

        return array_map(fn (array $item): array => $item + [
            'status' => 'active',
            'created_at' => '21 Oct 2026',
        ], $items);
    }
}
