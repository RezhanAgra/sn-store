<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

test('guest adding to cart is redirected to login', function () {
    $product = Product::factory()->create(['stock' => 5]);

    $this->post(route('cart.store'), ['product_id' => $product->id])->assertRedirect(route('login'));

    expect(CartItem::count())->toBe(0);
});

test('guest cannot open the cart or checkout', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with(['cart.index', 'checkout.index']);

test('logged in user can add a product to the cart and quantities merge', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 5]);

    $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id])->assertSessionHas('status');
    $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2]);

    expect($user->cartItems()->count())->toBe(1)->and($user->cartItems()->first()->quantity)->toBe(3);
});

test('out of stock products cannot be added to the cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 0]);

    $this->actingAs($user)
        ->post(route('cart.store'), ['product_id' => $product->id])
        ->assertSessionHasErrors('cart');

    expect($user->cartItems()->count())->toBe(0);
});

test('cart quantity cannot exceed available stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 2]);

    $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 3])
        ->assertSessionHasErrors('cart');
    expect($user->cartItems()->count())->toBe(0);
});

test('cart page lists items and the total', function () {
    $user = User::factory()->create();
    $item = CartItem::factory()->for($user)->create(['quantity' => 2]);
    $item->product->update(['name' => 'Laptop Zenith', 'price' => 1000000, 'stock' => 5]);

    $this->actingAs($user)
        ->get(route('cart.index'))
        ->assertOk()
        ->assertSee('Laptop Zenith')
        ->assertSee('Rp2.000.000');
});

test('user can update cart quantity within stock and not beyond', function () {
    $user = User::factory()->create();
    $item = CartItem::factory()->for($user)->for(Product::factory()->state(['stock' => 4]))->create();

    $this->actingAs($user)->patch(route('cart.update', $item), ['quantity' => 4])->assertSessionHas('status');
    expect($item->fresh()->quantity)->toBe(4);

    $this->actingAs($user)->patch(route('cart.update', $item), ['quantity' => 5])->assertSessionHasErrors('cart');
    $this->actingAs($user)->patch(route('cart.update', $item), ['quantity' => 0])->assertSessionHasErrors('quantity');
    expect($item->fresh()->quantity)->toBe(4);
});

test('user can remove an item from the cart', function () {
    $user = User::factory()->create();
    $item = CartItem::factory()->for($user)->create();

    $this->actingAs($user)->delete(route('cart.destroy', $item))->assertSessionHas('status');

    $this->assertModelMissing($item);
});

test('user cannot touch another users cart item', function () {
    $item = CartItem::factory()->create();

    $this->actingAs(User::factory()->create())->patch(route('cart.update', $item), ['quantity' => 1])->assertNotFound();
    $this->actingAs(User::factory()->create())->delete(route('cart.destroy', $item))->assertNotFound();

    $this->assertModelExists($item);
});

test('navbar shows the cart count for logged in users', function () {
    $user = User::factory()->create();
    CartItem::factory()->for($user)->create(['quantity' => 3]);

    $this->actingAs($user)->get(route('home'))->assertSee('Keranjang (3 produk)');
});
