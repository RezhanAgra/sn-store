<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    private const IMAGE_DISK = 'public';

    private const IMAGE_DIRECTORY = 'products';

    /**
     * Ambil semua produk beserta kategorinya, terbaru lebih dulu.
     *
     * @return Collection<int, Product>
     */
    public function all(): Collection
    {
        return Product::with('category')->latest()->get();
    }

    /**
     * @param  array{name: string, category_id: int, price: int, stock: int, description?: string|null, image?: UploadedFile|null}  $data
     */
    public function create(array $data): Product
    {
        return Product::create($this->withStoredImage($data));
    }

    /**
     * Perbarui produk. Gambar lama dihapus jika diganti dengan gambar baru.
     *
     * @param  array{name: string, category_id: int, price: int, stock: int, description?: string|null, image?: UploadedFile|null}  $data
     */
    public function update(Product $product, array $data): Product
    {
        $data = $this->withStoredImage($data);

        if (isset($data['image'])) {
            $this->deleteImage($product);
        }

        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $this->deleteImage($product);

        $product->delete();
    }

    /**
     * Ganti file unggahan pada data dengan path gambar yang tersimpan.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withStoredImage(array $data): array
    {
        if (($data['image'] ?? null) instanceof UploadedFile) {
            $data['image'] = $data['image']->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
        } else {
            unset($data['image']);
        }

        return $data;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image) {
            Storage::disk(self::IMAGE_DISK)->delete($product->image);
        }
    }
}
