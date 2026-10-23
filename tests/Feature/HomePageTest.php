<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('home page renders storefront sections', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Produk Terbaru');
    $response->assertSee('Siap Upgrade Gadget Anda?');
    $response->assertSee('Kategori');
});

test('home page lists ten latest products', function () {
    $response = $this->get(route('home'));

    expect($response->viewData('products'))->toHaveCount(10);
    expect($response->viewData('slides'))->toHaveCount(3);
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
