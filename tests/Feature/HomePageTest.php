<?php

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
