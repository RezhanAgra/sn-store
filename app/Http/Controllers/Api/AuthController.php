<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Http\Requests\Auth\ApiRegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Daftarkan pengguna baru.
     */
    public function register(ApiRegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return ApiResponse::success(new UserResource($user), 'Registrasi berhasil.', 201);
    }

    /**
     * Login dan kembalikan bearer token.
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated('email'), $request->validated('password'));

        if ($result === null) {
            return ApiResponse::error('Email atau password salah.', 401);
        }

        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'token_type' => 'Bearer',
            'token' => $result['token'],
        ], 'Login berhasil.');
    }
}
