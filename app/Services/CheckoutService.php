<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class CheckoutService
{
    public function __construct(private PaymentService $paymentService, private ShippingService $shippingService, private CartService $cartService) {}

    /**
     * Buat pesanan dari isi keranjang: validasi stok, kurangi stok, kosongkan keranjang,
     * lalu minta sesi pembayaran ke Midtrans.
     *
     * @param  array{name: string, phone: string, email: string, address: string, province_code: string, city_code: string, district_code: string, village_code: string, postal_code: string, shipping_option: string}  $data
     */
    public function checkout(User $user, array $data): Order
    {
        $shipping = $this->shippingService->findOption(
            $data['village_code'],
            $data['postal_code'],
            $this->cartService->weight($user->cartItems()->get()),
            $data['shipping_option'],
        );

        $order = DB::transaction(function () use ($user, $data, $shipping): Order {
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

            $order = $user->orders()->create($this->orderAttributes($data) + [
                'code' => $this->generateCode(),
                'shipping_courier' => $shipping['courier_name'],
                'shipping_service' => $shipping['service'],
                'shipping_cost' => $shipping['cost'],
                'total' => $shipping['cost'] + $cartItems->sum(fn ($item): int => $products[$item->product_id]->price * $item->quantity),
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

    /**
     * Ganti kode wilayah dengan nama wilayahnya agar pesanan tetap utuh bila data wilayah berubah.
     *
     * @param  array<string, string>  $data
     * @return array<string, string>
     */
    private function orderAttributes(array $data): array
    {
        return [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'province' => Province::where('code', $data['province_code'])->value('name'),
            'city' => City::where('code', $data['city_code'])->value('name'),
            'district' => District::where('code', $data['district_code'])->value('name'),
            'village' => Village::where('code', $data['village_code'])->value('name'),
            'postal_code' => $data['postal_code'],
        ];
    }

    private function generateCode(): string
    {
        do {
            $code = 'INV-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Order::where('code', $code)->exists());

        return $code;
    }
}
