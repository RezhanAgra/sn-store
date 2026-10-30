<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Ambil semua pengguna beserta perannya, terbaru lebih dulu.
     *
     * @return Collection<int, User>
     */
    public function all(): Collection
    {
        return User::with('roles')->latest()->get();
    }
}
