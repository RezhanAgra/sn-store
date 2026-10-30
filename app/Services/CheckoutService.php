<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Buat pesanan dari isi keranjang: validasi stok, kurangi stok, kosongkan keranjang,
     * lalu minta sesi pembayaran ke Midtrans.
     *
     * @param  array{name: string, phone: string, email: string, address: string}  $data
     */
    public function checkout(User $user, array $data): Order
    {
        $order = DB::transaction(function () use ($user, $data): Order {
            $cartItems = $user->cartItems()->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Keranjang Anda masih kosong.']);
            }

            $products = Product::whereKey($cartItems->pluck('product_id'))->lockForUpdate()->get()->keyBy('id');

            foreach ($cartItems as $cartItem) {
                $product = $products[$cartItem->product_id];

                if ($cartItem->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi (tersisa {$product->stock}).",
                    ]);
                }
            }

            $order = $user->orders()->create($data + [
                'code' => $this->generateCode(),
                'total' => $cartItems->sum(fn ($item): int => $products[$item->product_id]->price * $item->quantity),
                'status' => OrderStatus::Pending,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = $products[$cartItem->product_id];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $cartItem->quantity,
                ]);

                $product->decrement('stock', $cartItem->quantity);
            }

            $user->cartItems()->delete();
            $user->update(['phone' => $data['phone'], 'address' => $data['address']]);

            return $order;
        });

        return $this->paymentService->preparePayment($order);
    }

    private function generateCode(): string
    {
        do {
            $code = 'INV-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Order::where('code', $code)->exists());

        return $code;
    }
}
