<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\AddToCartRequest;
use App\Http\Resources\Api\CartItemResource;
use App\Http\Responses\ApiResponse;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private ProductService $productService,
    ) {}

    /**
     * Tampilkan isi keranjang pengguna beserta total jumlah dan harga.
     */
    public function index(Request $request): JsonResponse
    {
        $items = $this->cartService->items($request->user());

        return ApiResponse::success([
            'items' => CartItemResource::collection($items),
            'total_quantity' => (int) $items->sum('quantity'),
            'total_price' => $this->cartService->total($items),
        ], 'Data keranjang berhasil diambil.');
    }

    /**
     * Tambahkan produk ke keranjang (jumlah digabung jika produk sudah ada).
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        $product = $this->productService->find($request->validated('product_id'));

        $item = $this->cartService->add($request->user(), $product, $request->validated('quantity') ?? 1);

        return ApiResponse::success(
            new CartItemResource($item->load('product')),
            "{$product->name} ditambahkan ke keranjang.",
            201,
        );
    }
}
