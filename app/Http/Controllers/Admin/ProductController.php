<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CategoryService $categoryService,
    ) {}

    /**
     * Tampilkan daftar produk.
     */
    public function index(): View
    {
        return view('pages.admin.products.index', ['products' => $this->productService->all()]);
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create(): View
    {
        return view('pages.admin.products.create', ['categories' => $this->categoryService->all()]);
    }

    /**
     * Simpan produk baru.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create($request->validated());

        return to_route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah produk.
     */
    public function edit(Product $product): View
    {
        return view('pages.admin.products.edit', [
            'product' => $product,
            'categories' => $this->categoryService->all(),
        ]);
    }

    /**
     * Perbarui produk.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update($product, $request->validated());

        return to_route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);

        return to_route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
