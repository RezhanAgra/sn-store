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
