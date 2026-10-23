<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin awal dan beri role admin (jalankan RolePermissionSeeder lebih dulu). Ganti password setelah login pertama.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Admin SN Store',
                'password' => 'password1234',
                'email_verified_at' => now(),
            ],
        );

        $admin->assignRole('admin');
    }
}
