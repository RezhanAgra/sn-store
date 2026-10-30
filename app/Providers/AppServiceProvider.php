<?php

namespace App\Providers;

use App\Services\Payment\MidtransPaymentGateway;
use App\Services\Payment\PaymentGateway;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, MidtransPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('cartItem', fn (string $value) => request()->user()->cartItems()->findOrFail($value));
        Route::bind('order', fn (string $value) => request()->user()->orders()->findOrFail($value));
    }
}
