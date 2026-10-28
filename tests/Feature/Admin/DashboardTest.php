<?php

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;

function createAdminUser(): User
{
    return User::factory()->create()->assignRole(Role::findOrCreate('admin', 'web'));
}

test('guest is redirected to login from admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('authenticated user can view admin dashboard with sidebar menu', function () {
    $user = createAdminUser();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Dashboard', 'Kategori', 'Produk', 'Transaksi', 'User']);
});

test('dashboard shows add button linking to the create transaction page', function () {
    $this->actingAs(createAdminUser())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Tambah Transaksi')
        ->assertSee(route('admin.transactions.create'), false);
});

test('authenticated user can view create transaction page', function () {
    $this->actingAs(createAdminUser())
        ->get(route('admin.transactions.create'))
        ->assertOk()
        ->assertSee('Nama Pelanggan');
});

test('guest cannot access admin product and transaction pages', function (string $routeName) {
    $this->get(route($routeName))->assertRedirect(route('login'));
})->with(['admin.products.index', 'admin.products.create', 'admin.transactions.index', 'admin.transactions.create']);

test('authenticated user can view product and transaction lists', function () {
    $this->actingAs(createAdminUser());

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Tambah Produk');

    $this->get(route('admin.transactions.index'))
        ->assertOk()
        ->assertSee('Tambah Transaksi')
        ->assertSee('INV-2410-0128');

    $this->get(route('admin.products.create'))->assertOk()->assertSee('Nama Produk');
});

test('authenticated user can view category list and create page', function () {
    Category::factory()->create(['name' => 'Audio & Headphone']);
    $this->actingAs(createAdminUser());

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Daftar Kategori')
        ->assertSee('Audio &amp; Headphone', false)
        ->assertSee('Tambah Kategori');

    $this->get(route('admin.categories.create'))->assertOk()->assertSee('Nama Kategori');
});

test('guest cannot access admin category pages', function () {
    $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
});

test('authenticated user can view demo user list', function () {
    $this->actingAs(createAdminUser())
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Pengguna demo')
        ->assertSee('5 akun contoh')
        ->assertSee('Email contoh');
});

test('guest cannot access admin user page', function () {
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});

test('customer is redirected to the public page from every admin page', function (string $routeName) {
    $customer = User::factory()->create()->assignRole(Role::findOrCreate('customer', 'web'));

    $this->actingAs($customer)->get(route($routeName))->assertRedirect(route('home'));
})->with([
    'admin.dashboard',
    'admin.categories.index',
    'admin.products.index',
    'admin.transactions.index',
    'admin.users.index',
]);

test('user without any role is redirected to the public page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertRedirect(route('home'));
});
