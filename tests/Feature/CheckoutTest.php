<?php

use App\Enums\OrderStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Http;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

beforeEach(function () {
    config(['services.midtrans.server_key' => 'test-server-key', 'services.rajaongkir.key' => 'test-ongkir-key']);
    Http::fake([
        'rajaongkir.komerce.id/api/v1/destination/domestic-destination*' => Http::response(['data' => [
            ['id' => 555, 'subdistrict_name' => 'LAINNYA', 'zip_code' => '40152'],
            ['id' => 556, 'subdistrict_name' => 'GEGERKALONG 1', 'zip_code' => '40152'],
        ]]),
        'rajaongkir.komerce.id/api/v1/calculate/domestic-cost' => Http::response(['data' => [
            ['name' => 'SiCepat Express', 'code' => 'sicepat', 'service' => 'REG', 'description' => 'Reguler', 'cost' => 44800, 'etd' => '7-10 day'],
            ['name' => 'Jalur Nugraha Ekakurir (JNE)', 'code' => 'jne', 'service' => 'REG', 'description' => 'Layanan Reguler', 'cost' => 22000, 'etd' => '4 day'],
        ]]),
        'app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'snap-token',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/snap-token',
        ], 201),
    ]);
});

function makeRegion(string $suffix = '1'): array
{
    $province = Province::create(['code' => '3'.$suffix, 'name' => 'JAWA BARAT '.$suffix]);
    $city = City::create(['code' => '3'.$suffix.'01', 'province_code' => $province->code, 'name' => 'KOTA BANDUNG '.$suffix]);
    $district = District::create(['code' => '3'.$suffix.'01010', 'city_code' => $city->code, 'name' => 'SUKASARI '.$suffix]);
    $village = Village::create(['code' => '3'.$suffix.'01010001', 'district_code' => $district->code, 'name' => 'GEGERKALONG '.$suffix]);

    return [$province, $city, $district, $village];
}

function checkoutPayload(array $overrides = []): array
{
    [$province, $city, $district, $village] = Province::exists() ? [
        Province::first(), City::first(), District::first(), Village::first(),
    ] : makeRegion();

    return array_merge([
        'name' => 'Budi Santoso',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jl. Merdeka No. 1, Jakarta',
        'province_code' => $province->code,
        'city_code' => $city->code,
        'district_code' => $district->code,
        'village_code' => $village->code,
        'postal_code' => '40152',
        'shipping_option' => 'jne|REG',
    ], $overrides);
}

test('checkout form is prefilled with account data', function () {
    $user = User::factory()->create(['name' => 'Siti Rahma', 'email' => 'siti@example.com', 'phone' => '081111111111', 'address' => 'Bandung']);
    CartItem::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('checkout.index'))
        ->assertOk()
        ->assertSee('Siti Rahma')
        ->assertSee('siti@example.com')
        ->assertSee('081111111111')
        ->assertSee('Bandung');
});

test('checkout with an empty cart redirects back to the cart', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('checkout.index'))
        ->assertRedirect(route('cart.index'));
});

test('successful checkout creates the order, reduces stock and clears the cart', function () {
    $user = User::factory()->create();
    $laptop = Product::factory()->create(['price' => 1000000, 'stock' => 5]);
    $mouse = Product::factory()->create(['price' => 250000, 'stock' => 10]);
    CartItem::factory()->for($user)->for($laptop)->create(['quantity' => 2]);
    CartItem::factory()->for($user)->for($mouse)->create(['quantity' => 1]);

    $response = $this->actingAs($user)->post(route('checkout.store'), checkoutPayload());

    $order = Order::firstOrFail();
    $response->assertRedirect(route('orders.show', $order));

    expect($order)
        ->user_id->toBe($user->id)
        ->total->toBe(2272000)
        ->shipping_cost->toBe(22000)
        ->shipping_courier->toBe('Jalur Nugraha Ekakurir (JNE)')
        ->shipping_service->toBe('REG')
        ->payment_url->toBe('https://app.sandbox.midtrans.com/snap/v4/redirection/snap-token')
        ->status->toBe(OrderStatus::Pending)
        ->name->toBe('Budi Santoso')
        ->province->toBe('JAWA BARAT 1')
        ->city->toBe('KOTA BANDUNG 1')
        ->district->toBe('SUKASARI 1')
        ->village->toBe('GEGERKALONG 1')
        ->postal_code->toBe('40152')
        ->and($order->items)->toHaveCount(2)
        ->and($laptop->fresh()->stock)->toBe(3)
        ->and($mouse->fresh()->stock)->toBe(9)
        ->and($user->cartItems()->count())->toBe(0)
        ->and($user->fresh())->phone->toBe('081234567890')->address->toBe('Jl. Merdeka No. 1, Jakarta');
});

test('checkout fails and changes nothing when stock is insufficient', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 1]);
    CartItem::factory()->for($user)->for($product)->create(['quantity' => 2]);

    $this->actingAs($user)
        ->post(route('checkout.store'), checkoutPayload())
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(1)
        ->and($user->cartItems()->count())->toBe(1);
});

test('checkout with an empty cart creates no order', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('checkout.store'), checkoutPayload())
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
});

test('checkout validates the form', function (array $overrides, string $field) {
    $user = User::factory()->create();
    CartItem::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('checkout.store'), checkoutPayload($overrides))
        ->assertSessionHasErrors($field);
})->with([
    'missing name' => [['name' => ''], 'name'],
    'invalid phone' => [['phone' => '12345'], 'phone'],
    'invalid email' => [['email' => 'bukan-email'], 'email'],
    'missing address' => [['address' => ''], 'address'],
    'missing province' => [['province_code' => ''], 'province_code'],
    'unknown province' => [['province_code' => '99'], 'province_code'],
    'missing city' => [['city_code' => ''], 'city_code'],
    'missing district' => [['district_code' => ''], 'district_code'],
    'missing village' => [['village_code' => ''], 'village_code'],
    'missing postal code' => [['postal_code' => ''], 'postal_code'],
    'invalid postal code' => [['postal_code' => '12ab'], 'postal_code'],
    'missing shipping option' => [['shipping_option' => ''], 'shipping_option'],
    'unavailable shipping option' => [['shipping_option' => 'tiki|ECO'], 'shipping_option'],
]);

test('checkout rejects a region that does not belong to the selected parent', function () {
    $user = User::factory()->create();
    CartItem::factory()->for($user)->create();
    [$province] = makeRegion('1');
    [, $otherCity] = makeRegion('2');

    $this->actingAs($user)
        ->post(route('checkout.store'), checkoutPayload(['province_code' => $province->code, 'city_code' => $otherCity->code]))
        ->assertSessionHasErrors('city_code');

    expect(Order::count())->toBe(0);
});

test('region endpoints return the children of the selected parent', function () {
    [$province, $city, $district] = makeRegion('1');
    makeRegion('2');
    $user = User::factory()->create();

    $this->actingAs($user)->getJson(route('regions.cities', $province->code))
        ->assertOk()->assertExactJson([['code' => $city->code, 'name' => 'KOTA BANDUNG 1']]);
    $this->actingAs($user)->getJson(route('regions.districts', $city->code))
        ->assertOk()->assertJsonCount(1)->assertJsonPath('0.code', $district->code);
    $this->actingAs($user)->getJson(route('regions.villages', $district->code))
        ->assertOk()->assertJsonCount(1)->assertJsonPath('0.name', 'GEGERKALONG 1');

    auth()->logout();
    $this->getJson(route('regions.cities', $province->code))->assertUnauthorized();
});

test('checkout form lists provinces', function () {
    makeRegion();
    CartItem::factory()->for($user = User::factory()->create())->create();

    $this->actingAs($user)->get(route('checkout.index'))->assertOk()->assertSee('JAWA BARAT 1')->assertSee('Kode Pos');
});

test('order page shows the pay button to its owner only', function () {
    $order = Order::factory()->create(['payment_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/abc']);
    Http::fake(['api.sandbox.midtrans.com/*' => Http::response(['transaction_status' => 'pending'])]);

    $this->actingAs($order->user)
        ->get(route('orders.show', $order))
        ->assertOk()
        ->assertSee($order->code)
        ->assertSee('Bayar Sekarang')
        ->assertSee('https://app.sandbox.midtrans.com/snap/v4/redirection/abc');

    $this->actingAs(User::factory()->create())->get(route('orders.show', $order))->assertNotFound();
});

test('midtrans receives the order details when creating the payment', function () {
    $user = User::factory()->create();
    CartItem::factory()->for($user)->for(Product::factory()->state(['name' => 'Laptop', 'price' => 1000000, 'stock' => 5]))->create(['quantity' => 2]);

    $this->actingAs($user)->post(route('checkout.store'), checkoutPayload());

    $order = Order::firstOrFail();

    Http::assertSent(fn ($request) => $request->url() === 'https://app.sandbox.midtrans.com/snap/v1/transactions'
        && $request->hasHeader('Authorization', 'Basic '.base64_encode('test-server-key:'))
        && $request['transaction_details'] === ['order_id' => $order->code, 'gross_amount' => 2022000]
        && $request['item_details'][0]['price'] === 1000000
        && $request['item_details'][1] === ['id' => 'shipping', 'name' => 'Ongkir Jalur Nugraha Ekakurir (JNE) REG', 'price' => 22000, 'quantity' => 1]
        && $request['customer_details']['email'] === 'budi@example.com');
});

test('shipping cost endpoint returns options sorted by price, shipped from makassar', function () {
    [, , , $village] = makeRegion();
    CartItem::factory()->for($user = User::factory()->create())->create(['quantity' => 3]);

    $this->actingAs($user)
        ->postJson(route('checkout.shipping-cost'), ['village_code' => $village->code, 'postal_code' => '40152'])
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonPath('0.key', 'jne|REG')
        ->assertJsonPath('0.cost', 22000)
        ->assertJsonPath('1.key', 'sicepat|REG');

    Http::assertSent(fn ($request) => str_ends_with($request->url(), '/calculate/domestic-cost')
        && $request->hasHeader('key', 'test-ongkir-key')
        && $request['origin'] === 79234
        && $request['destination'] === 556
        && $request['weight'] === 3000
        && $request['courier'] === 'jne:sicepat:jnt');
});

test('shipping cost endpoint fails gracefully when rajaongkir is down', function () {
    [, , , $village] = makeRegion();
    CartItem::factory()->for($user = User::factory()->create())->create();
    Http::swap(new HttpFactory);
    Http::fake(['rajaongkir.komerce.id/*' => Http::response([], 500)]);

    $this->actingAs($user)
        ->postJson(route('checkout.shipping-cost'), ['village_code' => $village->code, 'postal_code' => '40152'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('shipping_option');
});

test('checkout is blocked and creates no order when shipping cannot be calculated', function () {
    $user = User::factory()->create();
    CartItem::factory()->for($user)->create();
    $payload = checkoutPayload();
    Http::swap(new HttpFactory);
    Http::fake(['rajaongkir.komerce.id/*' => Http::response([], 500)]);

    $this->actingAs($user)->post(route('checkout.store'), $payload)->assertSessionHasErrors('shipping_option');

    expect(Order::count())->toBe(0);
});
