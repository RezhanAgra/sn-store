<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.midtrans.server_key' => 'test-server-key']);
});

function notificationPayload(Order $order, string $status, array $overrides = []): array
{
    $statusCode = '200';
    $grossAmount = $order->total.'.00';

    return array_merge([
        'order_id' => $order->code,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => hash('sha512', $order->code.$statusCode.$grossAmount.'test-server-key'),
        'transaction_status' => $status,
    ], $overrides);
}

function orderWithItem(int $stock = 3, int $quantity = 2): array
{
    $product = Product::factory()->create(['stock' => $stock]);
    $order = Order::factory()->create(['total' => 100000]);
    OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'product_name' => $product->name, 'price' => 50000, 'quantity' => $quantity]);

    return [$order, $product];
}

test('settlement notification marks the order as paid without a csrf token', function () {
    [$order] = orderWithItem();

    $this->postJson(route('midtrans.notification'), notificationPayload($order, 'settlement'))->assertOk();

    expect($order->fresh()->status)->toBe(OrderStatus::Paid);
});

test('notification with an invalid signature is rejected', function () {
    [$order] = orderWithItem();

    $this->postJson(route('midtrans.notification'), notificationPayload($order, 'settlement', ['signature_key' => 'palsu']))
        ->assertForbidden();

    expect($order->fresh()->status)->toBe(OrderStatus::Pending);
});

test('expired or cancelled notification cancels the order and restores stock once', function (string $status) {
    [$order, $product] = orderWithItem(stock: 3, quantity: 2);

    $this->postJson(route('midtrans.notification'), notificationPayload($order, $status))->assertOk();
    $this->postJson(route('midtrans.notification'), notificationPayload($order, $status))->assertOk();

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled)
        ->and($product->fresh()->stock)->toBe(5);
})->with(['expire', 'cancel', 'deny']);

test('a paid order cannot be cancelled by a late notification', function () {
    [$order, $product] = orderWithItem();
    $order->update(['status' => OrderStatus::Paid]);

    $this->postJson(route('midtrans.notification'), notificationPayload($order, 'expire'))->assertOk();

    expect($order->fresh()->status)->toBe(OrderStatus::Paid)->and($product->fresh()->stock)->toBe(3);
});

test('pending notification keeps the order pending', function () {
    [$order] = orderWithItem();

    $this->postJson(route('midtrans.notification'), notificationPayload($order, 'pending'))->assertOk();

    expect($order->fresh()->status)->toBe(OrderStatus::Pending);
});

test('viewing a pending order syncs its status from midtrans', function () {
    [$order] = orderWithItem();
    $order->update(['payment_url' => 'https://pay.test/abc']);
    Http::fake(["api.sandbox.midtrans.com/v2/{$order->code}/status" => Http::response(['transaction_status' => 'settlement'])]);

    $this->actingAs($order->user)->get(route('orders.show', $order))->assertOk()->assertSee('Pembayaran diterima');

    expect($order->fresh()->status)->toBe(OrderStatus::Paid);
});
