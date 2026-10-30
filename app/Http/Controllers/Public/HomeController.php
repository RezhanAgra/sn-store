<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    private const LATEST_PRODUCT_LIMIT = 10;

    public function __construct(private ProductService $productService) {}

    /**
     * Tampilkan halaman beranda.
     */
    public function index(): View
    {
        return view('pages.public.home', [
            'slides' => config('storefront.slides'),
            'products' => $this->productService->latest(self::LATEST_PRODUCT_LIMIT),
        ]);
    }
}
