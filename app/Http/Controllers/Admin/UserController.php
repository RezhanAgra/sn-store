<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Tampilkan daftar pengguna.
     */
    public function index(): View
    {
        return view('pages.admin.users.index', ['users' => $this->userService->all()]);
    }
}
