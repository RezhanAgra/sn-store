<?php

use App\Models\Category;

test('admin can create a category', function () {
    $this->actingAs(createAdminUser())
        ->post(route('admin.categories.store'), ['name' => 'Audio & Headphone', 'description' => null])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('categories', ['name' => 'Audio & Headphone', 'slug' => 'audio-headphone']);
});

test('category creation requires a name', function () {
    $this->actingAs(createAdminUser())
        ->post(route('admin.categories.store'), ['description' => 'x'])
        ->assertSessionHasErrors('name');
});

test('slug is generated automatically and made unique', function () {
    $admin = createAdminUser();

    $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Laptop', 'slug' => 'manual']);
    $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Laptop']);

    expect(Category::orderBy('id')->pluck('slug')->all())->toBe(['laptop', 'laptop-2']);
});

test('admin can view the edit form with existing data', function () {
    $category = Category::factory()->create(['name' => 'Kamera']);

    $this->actingAs(createAdminUser())
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertSee('Kamera');
});

test('admin can update a category and slug follows the name', function () {
    $category = Category::factory()->create(['name' => 'Kamera', 'slug' => 'kamera']);

    $this->actingAs(createAdminUser())
        ->put(route('admin.categories.update', $category), ['name' => 'Kamera', 'description' => 'Baru'])
        ->assertRedirect(route('admin.categories.index'));

    expect($category->fresh())->slug->toBe('kamera')->description->toBe('Baru');

    $this->actingAs(createAdminUser())
        ->put(route('admin.categories.update', $category), ['name' => 'Kamera Digital']);

    expect($category->fresh()->slug)->toBe('kamera-digital');
});

test('admin can delete a category', function () {
    $category = Category::factory()->create();

    $this->actingAs(createAdminUser())
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertModelMissing($category);
});

test('guests and customers cannot modify categories', function () {
    $category = Category::factory()->create();

    $this->post(route('admin.categories.store'), ['name' => 'X'])->assertRedirect(route('login'));
    $this->delete(route('admin.categories.destroy', $category))->assertRedirect(route('login'));

    $this->assertModelExists($category);
});
