<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CheckoutRequest;
use App\Http\Requests\Public\ShippingCostRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\ShippingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CheckoutService $checkoutService,
        private ShippingService $shippingService,
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
            'provinces' => Province::orderBy('name')->get(['code', 'name']),
        ]);
    }

    /**
     * Hitung opsi ongkos kirim dari Makassar ke desa/kelurahan tujuan.
     */
    public function shippingCost(ShippingCostRequest $request): JsonResponse
    {
        $weight = $this->cartService->weight($this->cartService->items($request->user()));

        return response()->json($this->shippingService->options($request->validated('village_code'), $request->validated('postal_code'), $weight));
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
