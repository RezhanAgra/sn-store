<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MidtransPaymentGateway implements PaymentGateway
{
    private const EXPIRY_HOURS = 24;

    public function createPayment(Order $order): string
    {
        $order->loadMissing('items');

        $response = $this->client()->post($this->baseUrl('app').'/snap/v1/transactions', [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => $order->total,
            ],
            'item_details' => $order->items->map(fn (OrderItem $item): array => [
                'id' => (string) $item->product_id,
                'name' => Str::limit($item->product_name, 50, ''),
                'price' => $item->price,
                'quantity' => $item->quantity,
            ])->all(),
            'customer_details' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'shipping_address' => ['address' => $order->address, 'phone' => $order->phone],
            ],
            'callbacks' => ['finish' => route('orders.show', $order)],
            'expiry' => ['unit' => 'hours', 'duration' => self::EXPIRY_HOURS],
        ])->throw();

        return $response->json('redirect_url');
    }

    public function isValidNotification(array $payload): bool
    {
        $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$this->serverKey());

        return hash_equals($expected, (string) $payload['signature_key']);
    }

    public function fetchStatus(Order $order): array
    {
        return $this->client()->get($this->baseUrl('api')."/v2/{$order->code}/status")->throw()->json();
    }

    private function client(): PendingRequest
    {
        return Http::withBasicAuth($this->serverKey(), '')->acceptJson()->asJson()->timeout(15);
    }

    private function serverKey(): string
    {
        return (string) config('services.midtrans.server_key');
    }

    /**
     * @param  'app'|'api'  $subdomain
     */
    private function baseUrl(string $subdomain): string
    {
        $sandbox = config('services.midtrans.is_production') ? '' : 'sandbox.';

        return "https://{$subdomain}.{$sandbox}midtrans.com";
    }
}
