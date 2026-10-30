<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ProductIndexRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CategoryService $categoryService,
    ) {}

    /**
     * Tampilkan daftar produk dengan filter kategori dan pencarian.
     */
    public function index(ProductIndexRequest $request): View
    {
        $filters = $request->validated();

        return view('pages.public.products.index', [
            'products' => $this->productService->paginate($filters['q'] ?? null, $filters['category'] ?? null),
            'categories' => $this->categoryService->all(),
            'search' => $filters['q'] ?? '',
            'activeCategory' => $filters['category'] ?? null,
        ]);
    }
}
