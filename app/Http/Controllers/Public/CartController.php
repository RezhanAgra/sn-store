<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\AddToCartRequest;
use App\Http\Requests\Public\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /**
     * Tampilkan isi keranjang.
     */
    public function index(Request $request): View
    {
        $items = $this->cartService->items($request->user());

        return view('pages.public.cart.index', [
            'items' => $items,
            'total' => $this->cartService->total($items),
        ]);
    }

    /**
     * Tambahkan produk ke keranjang.
     */
    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->validated('product_id'));

        $this->cartService->add($request->user(), $product, $request->validated('quantity') ?? 1);

        return back()->with('status', "{$product->name} ditambahkan ke keranjang.");
    }

    /**
     * Ubah jumlah produk di keranjang.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->cartService->updateQuantity($cartItem, $request->validated('quantity'));

        return back()->with('status', 'Jumlah produk diperbarui.');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->remove($cartItem);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }
}
