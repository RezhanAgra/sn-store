<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Ambil isi keranjang pengguna beserta produknya.
     *
     * @return Collection<int, CartItem>
     */
    public function items(User $user): Collection
    {
        return $user->cartItems()->with('product')->latest()->latest('id')->get();
    }

    /**
     * Jumlah total produk (akumulasi qty) di keranjang.
     */
    public function count(?User $user): int
    {
        return $user ? (int) $user->cartItems()->sum('quantity') : 0;
    }

    /**
     * Total harga seluruh item keranjang.
     *
     * @param  Collection<int, CartItem>  $items
     */
    public function total(Collection $items): int
    {
        return $items->sum(fn (CartItem $item): int => $item->subtotal);
    }

    /**
     * Tambahkan produk ke keranjang. Qty digabung jika produk sudah ada.
     */
    public function add(User $user, Product $product, int $quantity): CartItem
    {
        if ($product->stock < 1) {
            throw ValidationException::withMessages(['cart' => "Stok {$product->name} sedang habis."]);
        }

        $item = $user->cartItems()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;

        $this->ensureStockAvailable($product, $item->quantity);

        $item->save();

        return $item;
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        $this->ensureStockAvailable($item->product, $quantity);

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    private function ensureStockAvailable(Product $product, int $quantity): void
    {
        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'cart' => "Stok {$product->name} hanya tersisa {$product->stock}.",
            ]);
        }
    }
}
