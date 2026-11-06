<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('guests cannot access the cart', function () {
    $this->getJson(route('api.cart.index'))
        ->assertUnauthorized()
        ->assertJsonPath('status', 'error');

    $this->postJson(route('api.cart.store'), ['product_id' => 1])->assertUnauthorized();
});

test('a user can add a product to the cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    Sanctum::actingAs($user);

    $this->postJson(route('api.cart.store'), ['product_id' => $product->id, 'quantity' => 2])
        ->assertCreated()
        ->assertJsonPath('code', 201)
        ->assertJsonPath('data.quantity', 2)
        ->assertJsonPath('data.product.id', $product->id);

    expect($user->cartItems()->count())->toBe(1);
});

test('adding the same product merges the quantity', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    Sanctum::actingAs($user);

    $this->postJson(route('api.cart.store'), ['product_id' => $product->id, 'quantity' => 2]);
    $this->postJson(route('api.cart.store'), ['product_id' => $product->id])
        ->assertJsonPath('data.quantity', 3);
});

test('adding more than the available stock is rejected', function () {
    $product = Product::factory()->create(['stock' => 1]);
    Sanctum::actingAs(User::factory()->create());

    $this->postJson(route('api.cart.store'), ['product_id' => $product->id, 'quantity' => 2])
        ->assertUnprocessable()
        ->assertJsonPath('status', 'error')
        ->assertJsonStructure(['data' => ['cart']]);
});

test('adding an unknown product is rejected', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson(route('api.cart.store'), ['product_id' => 999])
        ->assertUnprocessable()
        ->assertJsonStructure(['data' => ['product_id']]);
});

test('the cart lists only the user items with totals', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 10000]);
    CartItem::factory()->for($user)->for($product)->create(['quantity' => 3]);
    CartItem::factory()->create();
    Sanctum::actingAs($user);

    $this->getJson(route('api.cart.index'))
        ->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.subtotal', 30000)
        ->assertJsonPath('data.total_quantity', 3)
        ->assertJsonPath('data.total_price', 30000);
});
