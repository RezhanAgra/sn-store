<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna.
     */
    public function index(): View
    {
        return view('pages.admin.users.index', ['users' => collect($this->users())]);
    }

    /**
     * Data dummy pengguna. Ganti dengan User::latest()->get() saat siap memakai data asli.
     *
     * @return array<int, array{name: string, email: string, role: string, status: string}>
     */
    private function users(): array
    {
        return [
            ['name' => 'Admin SN Store', 'email' => 'admin@example.com', 'role' => 'Administrator', 'status' => 'active'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'Customer', 'status' => 'active'],
            ['name' => 'Siti Rahma', 'email' => 'siti@example.com', 'role' => 'Customer', 'status' => 'active'],
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'role' => 'Customer', 'status' => 'active'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'role' => 'Customer', 'status' => 'active'],
        ];
    }
}
