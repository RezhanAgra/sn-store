<?php

use App\Http\Controllers\Payment\MidtransNotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\OrderController;
use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');

Route::post('/midtrans/notification', MidtransNotificationController::class)->name('midtrans.notification');

Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/pesanan/{order}/bayar', [OrderController::class, 'pay'])->name('orders.pay');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
