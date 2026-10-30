<?php

use App\Enums\OrderStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;

test('order stays pending and can retry payment when midtrans fails', function () {
    config(['services.midtrans.server_key' => 'test-server-key']);
    Http::fakeSequence('app.sandbox.midtrans.com/*')
        ->push(['error_messages' => ['gagal']], 500)
        ->push(['redirect_url' => 'https://pay.test/abc'], 201);

    $user = User::factory()->create();
    CartItem::factory()->for($user)->for(Product::factory()->state(['stock' => 5]))->create();

    $this->actingAs($user)->post(route('checkout.store'), [
        'name' => 'Budi',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jakarta',
    ])->assertRedirect();

    $order = Order::firstOrFail();
    expect($order->payment_url)->toBeNull()->and($order->status)->toBe(OrderStatus::Pending);

    $this->actingAs($user)->post(route('orders.pay', $order))->assertRedirect('https://pay.test/abc');

    expect($order->fresh()->payment_url)->toBe('https://pay.test/abc');
});

test('user cannot retry payment for another users order', function () {
    $order = Order::factory()->create();

    $this->actingAs(User::factory()->create())->post(route('orders.pay', $order))->assertNotFound();
});
