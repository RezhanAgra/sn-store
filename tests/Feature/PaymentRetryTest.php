<?php

use App\Enums\OrderStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

test('order stays pending and can retry payment when midtrans fails', function () {
    config(['services.midtrans.server_key' => 'test-server-key']);
    Http::fake([
        'rajaongkir.komerce.id/api/v1/destination/domestic-destination*' => Http::response(['data' => [['id' => 556, 'subdistrict_name' => 'GEGERKALONG', 'zip_code' => '40152']]]),
        'rajaongkir.komerce.id/api/v1/calculate/domestic-cost' => Http::response(['data' => [['name' => 'JNE', 'code' => 'jne', 'service' => 'REG', 'description' => 'Reguler', 'cost' => 22000, 'etd' => '4 day']]]),
    ]);
    Http::fakeSequence('app.sandbox.midtrans.com/*')
        ->push(['error_messages' => ['gagal']], 500)
        ->push(['redirect_url' => 'https://pay.test/abc'], 201);

    $user = User::factory()->create();
    CartItem::factory()->for($user)->for(Product::factory()->state(['stock' => 5]))->create();

    Province::create(['code' => '32', 'name' => 'JAWA BARAT']);
    City::create(['code' => '3273', 'province_code' => '32', 'name' => 'KOTA BANDUNG']);
    District::create(['code' => '3273010', 'city_code' => '3273', 'name' => 'SUKASARI']);
    Village::create(['code' => '3273010001', 'district_code' => '3273010', 'name' => 'GEGERKALONG']);

    $this->actingAs($user)->post(route('checkout.store'), [
        'name' => 'Budi',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jakarta',
        'province_code' => '32',
        'city_code' => '3273',
        'district_code' => '3273010',
        'village_code' => '3273010001',
        'postal_code' => '40152',
        'shipping_option' => 'jne|REG',
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
