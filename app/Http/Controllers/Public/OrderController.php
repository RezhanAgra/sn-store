<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Tampilkan detail pesanan milik pengguna yang login.
     */
    public function show(Order $order): View
    {
        $this->paymentService->syncStatus($order);

        return view('pages.public.orders.show', ['order' => $order->load('items')]);
    }

    /**
     * Buat ulang sesi pembayaran untuk pesanan yang belum memiliki link bayar.
     */
    public function pay(Order $order): RedirectResponse
    {
        $order = $this->paymentService->preparePayment($order);

        if (! $order->payment_url) {
            return back()->withErrors(['cart' => 'Pembayaran belum dapat diproses. Silakan coba lagi.']);
        }

        return redirect()->away($order->payment_url);
    }
}
