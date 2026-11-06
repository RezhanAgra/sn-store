<?php

use App\Models\Category;

test('categories can be listed publicly without authentication', function () {
    Category::factory()->create(['name' => 'Laptop', 'slug' => 'laptop', 'description' => 'Semua laptop']);

    $this->getJson(route('api.categories.index'))
        ->assertOk()
        ->assertJsonPath('code', 200)
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('message', 'Data kategori berhasil diambil.')
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'description', 'created_at', 'updated_at']]])
        ->assertJsonPath('data.0.slug', 'laptop');
});

test('a category can be shown by slug', function () {
    Category::factory()->create(['name' => 'Audio', 'slug' => 'audio']);

    $this->getJson(route('api.categories.show', 'audio'))
        ->assertOk()
        ->assertJsonPath('data.name', 'Audio');
});

test('showing an unknown category returns 404 in the standard format', function () {
    $this->getJson(route('api.categories.show', 'tidak-ada'))
        ->assertNotFound()
        ->assertExactJson(['code' => 404, 'status' => 'error', 'message' => 'Data tidak ditemukan.', 'data' => null]);
});
