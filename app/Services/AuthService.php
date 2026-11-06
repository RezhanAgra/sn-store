<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthService
{
    private const TOKEN_NAME = 'api-token';

    /**
     * Daftarkan pengguna baru dengan peran customer.
     *
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function register(array $data): User
    {
        $user = User::create($data);

        $user->assignRole(Role::findOrCreate('customer', 'web'));

        event(new Registered($user));

        return $user;
    }

    /**
     * Verifikasi kredensial lalu terbitkan bearer token. Null jika kredensial salah.
     *
     * @return array{user: User, token: string}|null
     */
    public function login(string $email, string $password): ?array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return [
            'user' => $user,
            'token' => $user->createToken(self::TOKEN_NAME)->plainTextToken,
        ];
    }
}
