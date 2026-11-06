<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('api.categories.show');

Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->whereNumber('id')->name('api.products.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('api.cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('api.cart.store');
});

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('api.login');
