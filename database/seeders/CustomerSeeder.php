<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Buat akun customer contoh dan beri role customer (jalankan RolePermissionSeeder lebih dulu).
     */
    public function run(): void
    {
        $customer = User::updateOrCreate(
            ['email' => 'customer@snstore.test'],
            [
                'name' => 'Customer SN Store',
                'password' => 'Customer@12345',
                'email_verified_at' => now(),
            ],
        );

        $customer->assignRole('customer');
    }
}
