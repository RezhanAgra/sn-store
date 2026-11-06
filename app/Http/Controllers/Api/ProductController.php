<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ProductIndexRequest;
use App\Http\Resources\Api\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    /**
     * Daftar produk beserta kategori, dengan pencarian (q) dan filter slug kategori (category).
     */
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();

        return ApiResponse::success(ProductResource::collection($this->productService->paginate($filters['q'] ?? null, $filters['category'] ?? null)), 'Data produk berhasil diambil.');
    }

    /**
     * Detail produk beserta kategorinya.
     */
    public function show(int $id): JsonResponse
    {
        return ApiResponse::success(new ProductResource($this->productService->find($id)), 'Detail produk berhasil diambil.');
    }
}
