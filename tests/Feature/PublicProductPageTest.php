<?php

use App\Models\Category;
use App\Models\Product;

test('product page lists products with sidebar categories and cart button', function () {
    $category = Category::factory()->create(['name' => 'Laptop', 'slug' => 'laptop']);
    Product::factory()->for($category)->create(['name' => 'Laptop Zenith']);

    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('Semua Produk')
        ->assertSee('Semua Kategori')
        ->assertSee('Laptop Zenith')
        ->assertSee('Tambah ke Keranjang')
        ->assertSee(route('products.index', ['category' => 'laptop']), false);
});

test('products can be filtered by category', function () {
    $laptop = Category::factory()->create(['slug' => 'laptop']);
    Product::factory()->for($laptop)->create(['name' => 'Laptop Zenith']);
    Product::factory()->create(['name' => 'Kamera Lumo']);

    $this->get(route('products.index', ['category' => 'laptop']))
        ->assertSee('Laptop Zenith')
        ->assertDontSee('Kamera Lumo');
});

test('products can be searched by name or category name', function () {
    $audio = Category::factory()->create(['name' => 'Audio']);
    Product::factory()->create(['name' => 'Speaker Boom']);
    Product::factory()->for($audio)->create(['name' => 'Headphone Aura']);

    $this->get(route('products.index', ['q' => 'boom']))
        ->assertSee('Speaker Boom')
        ->assertDontSee('Headphone Aura');

    $this->get(route('products.index', ['q' => 'audio']))
        ->assertSee('Headphone Aura')
        ->assertDontSee('Speaker Boom');
});

test('search and category filter work together', function () {
    $laptop = Category::factory()->create(['slug' => 'laptop']);
    Product::factory()->for($laptop)->create(['name' => 'Laptop Pro']);
    Product::factory()->for($laptop)->create(['name' => 'Laptop Air']);

    $this->get(route('products.index', ['category' => 'laptop', 'q' => 'pro']))
        ->assertSee('Laptop Pro')
        ->assertDontSee('Laptop Air');
});

test('product page shows empty state when nothing matches', function () {
    $this->get(route('products.index', ['q' => 'tidak-ada']))
        ->assertOk()
        ->assertSee('Produk tidak ditemukan');
});

test('product page paginates and keeps filters in page links', function () {
    $category = Category::factory()->create(['slug' => 'laptop']);
    Product::factory()->count(13)->for($category)->create();

    $response = $this->get(route('products.index', ['category' => 'laptop']));

    expect($response->viewData('products'))->toHaveCount(12);
    $response->assertSee('category=laptop&amp;page=2', false);
});

test('search query is validated', function () {
    $this->get(route('products.index', ['q' => str_repeat('a', 101)]))->assertSessionHasErrors('q');
});
