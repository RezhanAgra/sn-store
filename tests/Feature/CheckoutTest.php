<?php

use App\Enums\OrderStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.midtrans.server_key' => 'test-server-key']);
    Http::fake([
        'app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'snap-token',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/snap-token',
        ], 201),
    ]);
});

function checkoutPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Budi Santoso',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jl. Merdeka No. 1, Jakarta',
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
        ->total->toBe(2250000)
        ->payment_url->toBe('https://app.sandbox.midtrans.com/snap/v4/redirection/snap-token')
        ->status->toBe(OrderStatus::Pending)
        ->name->toBe('Budi Santoso')
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
]);

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
        && $request['transaction_details'] === ['order_id' => $order->code, 'gross_amount' => 2000000]
        && $request['item_details'][0]['price'] === 1000000
        && $request['customer_details']['email'] === 'budi@example.com');
});
