<?php

use App\Models\Category;
use App\Models\Product;

test('products can be listed publicly with their category', function () {
    $category = Category::factory()->create(['slug' => 'laptop']);
    Product::factory()->for($category)->create(['name' => 'Laptop Zenith']);

    $this->getJson(route('api.products.index'))
        ->assertOk()
        ->assertJsonPath('code', 200)
        ->assertJsonPath('status', 'success')
        ->assertJsonStructure(['meta', 'links', 'data' => [['id', 'name', 'description', 'price', 'stock', 'image_url', 'category' => ['id', 'name', 'slug'], 'created_at', 'updated_at']]])
        ->assertJsonPath('data.0.category.slug', 'laptop');
});

test('products can be filtered by category slug and searched by name', function () {
    $laptop = Category::factory()->create(['slug' => 'laptop']);
    Product::factory()->for($laptop)->create(['name' => 'Laptop Zenith']);
    Product::factory()->create(['name' => 'Kamera Lumo']);

    $this->getJson(route('api.products.index', ['category' => 'laptop']))
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Laptop Zenith');

    $this->getJson(route('api.products.index', ['q' => 'Kamera']))
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Kamera Lumo');
});

test('a product can be shown with its category', function () {
    $product = Product::factory()->for(Category::factory()->create(['name' => 'Audio']))->create();

    $this->getJson(route('api.products.show', $product->id))
        ->assertOk()
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.category.name', 'Audio');
});

test('showing an unknown product returns 404 in the standard format', function () {
    $this->getJson(route('api.products.show', 999))
        ->assertNotFound()
        ->assertExactJson(['code' => 404, 'status' => 'error', 'message' => 'Data tidak ditemukan.', 'data' => null]);
});

test('invalid filters return 422 in the standard format', function () {
    $this->getJson(route('api.products.index', ['q' => str_repeat('a', 101)]))
        ->assertUnprocessable()
        ->assertJsonPath('code', 422)
        ->assertJsonPath('status', 'error')
        ->assertJsonStructure(['data' => ['q']]);
});
