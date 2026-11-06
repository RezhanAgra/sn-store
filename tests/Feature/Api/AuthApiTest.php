<?php

use App\Models\User;

test('a user can register through the api', function () {
    $this->postJson(route('api.register'), [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => 'password-rahasia',
        'password_confirmation' => 'password-rahasia',
    ])
        ->assertCreated()
        ->assertJsonPath('code', 201)
        ->assertJsonPath('data.email', 'budi@example.com')
        ->assertJsonMissingPath('data.password');

    expect(User::firstWhere('email', 'budi@example.com')->hasRole('customer'))->toBeTrue();
});

test('registration validates input', function () {
    User::factory()->create(['email' => 'budi@example.com']);

    $this->postJson(route('api.register'), [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => 'password-rahasia',
        'password_confirmation' => 'beda',
    ])
        ->assertUnprocessable()
        ->assertJsonStructure(['data' => ['email', 'password']]);
});

test('login returns a bearer token that grants access to protected endpoints', function () {
    User::factory()->create(['email' => 'budi@example.com', 'password' => 'password-rahasia']);

    $token = $this->postJson(route('api.login'), ['email' => 'budi@example.com', 'password' => 'password-rahasia'])
        ->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonPath('data.user.email', 'budi@example.com')
        ->json('data.token');

    expect($token)->toBeString()->not->toBeEmpty();

    $this->withToken($token)->getJson(route('api.cart.index'))->assertOk();
});

test('login with wrong credentials returns 401', function () {
    User::factory()->create(['email' => 'budi@example.com', 'password' => 'password-rahasia']);

    $this->postJson(route('api.login'), ['email' => 'budi@example.com', 'password' => 'salah'])
        ->assertUnauthorized()
        ->assertExactJson(['code' => 401, 'status' => 'error', 'message' => 'Email atau password salah.', 'data' => null]);
});
