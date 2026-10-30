<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('home page renders storefront sections', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Produk Terbaru');
    $response->assertSee('Siap Upgrade Gadget Anda?');
    $response->assertSee('Kategori');
});

test('home page lists the ten latest products from the database', function () {
    Product::factory()->count(12)->create();
    $newest = Product::factory()->create(['name' => 'Produk Paling Baru']);

    $response = $this->get(route('home'));

    expect($response->viewData('products'))->toHaveCount(10)->and($response->viewData('products')->first()->is($newest))->toBeTrue();
    expect($response->viewData('slides'))->toHaveCount(3);
    $response->assertSee('Produk Paling Baru');
});

test('home page shows categories from the database in the navigation', function () {
    Category::factory()->create(['name' => 'Konsol Gim']);

    $this->get(route('home'))->assertSee('Konsol Gim');
});

test('home page shows an empty state and sold out products', function () {
    $this->get(route('home'))->assertSee('Belum ada produk.');

    Product::factory()->create(['stock' => 0]);

    $this->get(route('home'))->assertSee('Stok Habis')->assertDontSee('Belum ada produk.');
});

test('navbar shows login and register for guests only', function () {
    $this->get(route('home'))
        ->assertSee(route('register'), false)
        ->assertSee('Register')
        ->assertDontSee(route('logout'), false);
});

test('navbar shows logout for logged in users and hides register', function () {
    $admin = User::factory()->create()->assignRole(Role::findOrCreate('admin', 'web'));
    $customer = User::factory()->create()->assignRole(Role::findOrCreate('customer', 'web'));

    $this->actingAs($admin)->get(route('home'))
        ->assertSee(route('logout'), false)
        ->assertSee(route('admin.dashboard'), false)
        ->assertDontSee(route('register'), false);

    $this->actingAs($customer)->get(route('home'))
        ->assertSee(route('logout'), false)
        ->assertDontSee(route('admin.dashboard'), false)
        ->assertDontSee(route('register'), false);
});
