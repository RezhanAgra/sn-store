<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    /**
     * Tampilkan daftar kategori.
     */
    public function index(): View
    {
        return view('pages.admin.categories.index', ['categories' => $this->categoryService->all()]);
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create(): View
    {
        return view('pages.admin.categories.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());

        return to_route('admin.categories.index')->with('status', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah kategori.
     */
    public function edit(Category $category): View
    {
        return view('pages.admin.categories.edit', ['category' => $category]);
    }

    /**
     * Perbarui kategori.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->update($category, $request->validated());

        return to_route('admin.categories.index')->with('status', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->delete($category);

        return to_route('admin.categories.index')->with('status', 'Kategori berhasil dihapus.');
    }
}
