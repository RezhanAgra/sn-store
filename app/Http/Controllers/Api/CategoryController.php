<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Responses\ApiResponse;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    /**
     * Daftar seluruh kategori (publik).
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success(CategoryResource::collection($this->categoryService->all()), 'Data kategori berhasil diambil.');
    }

    /**
     * Detail kategori berdasarkan slug (publik).
     */
    public function show(string $slug): JsonResponse
    {
        return ApiResponse::success(new CategoryResource($this->categoryService->findBySlug($slug)), 'Detail kategori berhasil diambil.');
    }
}
