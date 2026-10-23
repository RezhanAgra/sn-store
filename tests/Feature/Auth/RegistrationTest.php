<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('new users registered through the form get the customer role', function () {
    $this->post('/register', [
        'name' => 'Customer Baru',
        'email' => 'baru@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'baru@example.com')->firstOrFail();

    expect($user->hasRole('customer'))->toBeTrue()
        ->and($user->getRoleNames()->all())->toBe(['customer']);
});
