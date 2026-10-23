<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin awal. Ganti password setelah login pertama.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@snstore.test'],
            [
                'name' => 'Admin SN Store',
                'password' => 'Admin@12345',
                'email_verified_at' => now(),
            ],
        );
    }
}
