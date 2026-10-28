<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

function productPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Smartphone Nova',
        'category_id' => Category::factory()->create()->id,
        'price' => 6499000,
        'stock' => 10,
        'description' => 'Ponsel andalan.',
        'image' => UploadedFile::fake()->image('nova.jpg'),
    ], $overrides);
}

test('admin can view the product list and create page', function () {
    Product::factory()->create(['name' => 'Laptop Zenith']);

    $this->actingAs(createAdminUser())
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Laptop Zenith')
        ->assertSee('Tambah Produk');

    $this->get(route('admin.products.create'))->assertOk()->assertSee('Nama Produk');
});

test('admin can create a product and the image is stored locally', function () {
    $this->actingAs(createAdminUser())
        ->post(route('admin.products.store'), productPayload())
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status');

    $product = Product::firstWhere('name', 'Smartphone Nova');

    expect($product)->not->toBeNull()->and($product->image)->toStartWith('products/');
    Storage::disk('public')->assertExists($product->image);
});

test('product creation validates input', function (array $overrides, string $field) {
    $this->actingAs(createAdminUser())
        ->post(route('admin.products.store'), productPayload($overrides))
        ->assertSessionHasErrors($field);
})->with([
    'missing name' => [['name' => ''], 'name'],
    'unknown category' => [['category_id' => 999], 'category_id'],
    'negative price' => [['price' => -1], 'price'],
    'negative stock' => [['stock' => -1], 'stock'],
    'missing image' => [['image' => null], 'image'],
    'non image file' => [['image' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf')], 'image'],
    'oversized image' => [['image' => UploadedFile::fake()->image('big.jpg')->size(3000)], 'image'],
]);

test('admin can update a product without replacing its image', function () {
    $image = UploadedFile::fake()->image('old.jpg')->store('products', 'public');
    $product = Product::factory()->create(['image' => $image]);

    $this->actingAs(createAdminUser())
        ->put(route('admin.products.update', $product), productPayload(['name' => 'Nama Baru', 'image' => null]))
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh())->name->toBe('Nama Baru')->image->toBe($image);
    Storage::disk('public')->assertExists($image);
});

test('replacing the image removes the old file', function () {
    $old = UploadedFile::fake()->image('old.jpg')->store('products', 'public');
    $product = Product::factory()->create(['image' => $old]);

    $this->actingAs(createAdminUser())
        ->put(route('admin.products.update', $product), productPayload(['image' => UploadedFile::fake()->image('new.jpg')]));

    Storage::disk('public')->assertMissing($old);
    Storage::disk('public')->assertExists($product->fresh()->image);
});

test('deleting a product removes its image', function () {
    $image = UploadedFile::fake()->image('old.jpg')->store('products', 'public');
    $product = Product::factory()->create(['image' => $image]);

    $this->actingAs(createAdminUser())
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertModelMissing($product);
    Storage::disk('public')->assertMissing($image);
});

test('deleting a category keeps its products', function () {
    $product = Product::factory()->create();

    $product->category->delete();

    expect($product->fresh()->category_id)->toBeNull();
});

test('guests cannot modify products', function () {
    $product = Product::factory()->create();

    $this->post(route('admin.products.store'), [])->assertRedirect(route('login'));
    $this->delete(route('admin.products.destroy', $product))->assertRedirect(route('login'));

    $this->assertModelExists($product);
});
