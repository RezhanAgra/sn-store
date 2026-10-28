<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Ambil semua kategori, terbaru lebih dulu.
     *
     * @return Collection<int, Category>
     */
    public function all(): Collection
    {
        return Category::latest()->get();
    }

    /**
     * Buat kategori baru dengan slug otomatis dari nama.
     *
     * @param  array{name: string, description?: string|null}  $data
     */
    public function create(array $data): Category
    {
        return Category::create($data + ['slug' => $this->generateUniqueSlug($data['name'])]);
    }

    /**
     * Perbarui kategori. Slug dibuat ulang hanya jika nama berubah.
     *
     * @param  array{name: string, description?: string|null}  $data
     */
    public function update(Category $category, array $data): Category
    {
        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category);
        }

        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    /**
     * Buat slug unik dari nama, menambahkan akhiran angka jika sudah dipakai.
     */
    private function generateUniqueSlug(string $name, ?Category $ignore = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $suffix = 2;

        while (Category::where('slug', $slug)->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
