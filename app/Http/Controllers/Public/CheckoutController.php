<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CheckoutService $checkoutService,
    ) {}

    /**
     * Tampilkan form checkout dengan data akun yang sudah terisi.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $items = $this->cartService->items($request->user());

        if ($items->isEmpty()) {
            return to_route('cart.index')->withErrors(['cart' => 'Keranjang Anda masih kosong.']);
        }

        return view('pages.public.checkout.index', [
            'items' => $items,
            'total' => $this->cartService->total($items),
            'user' => $request->user(),
        ]);
    }

    /**
     * Proses checkout dan buat pesanan.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->checkoutService->checkout($request->user(), $request->validated());

        return to_route('orders.show', $order)->with('status', 'Pesanan berhasil dibuat.');
    }
}
