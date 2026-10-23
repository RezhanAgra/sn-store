<?php

use App\Models\User;

test('guest is redirected to login from admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('authenticated user can view admin dashboard with sidebar menu', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Dashboard', 'Kategori', 'Produk', 'Transaksi', 'User']);
});

test('dashboard shows add button linking to the create transaction page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Tambah Transaksi')
        ->assertSee(route('admin.transactions.create'), false);
});

test('authenticated user can view create transaction page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.transactions.create'))
        ->assertOk()
        ->assertSee('Nama Pelanggan');
});

test('guest cannot access admin product and transaction pages', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with(['admin.products.index', 'admin.products.create', 'admin.transactions.index', 'admin.transactions.create']);

test('authenticated user can view product and transaction lists', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Tambah Produk')
        ->assertSee('Smartphone Nova X12 256GB');

    $this->get(route('admin.transactions.index'))
        ->assertOk()
        ->assertSee('Tambah Transaksi')
        ->assertSee('INV-2410-0128');

    $this->get(route('admin.products.create'))->assertOk()->assertSee('Nama Produk');
});

test('authenticated user can view category list and create page', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Daftar Kategori')
        ->assertSee('Audio & Headphone')
        ->assertSee('Tambah Kategori');

    $this->get(route('admin.categories.create'))->assertOk()->assertSee('Nama Kategori');
});

test('guest cannot access admin category pages', function () {
    $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
});

test('authenticated user can view demo user list', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Pengguna demo')
        ->assertSee('5 akun contoh')
        ->assertSee('Email contoh');
});

test('guest cannot access admin user page', function () {
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});
