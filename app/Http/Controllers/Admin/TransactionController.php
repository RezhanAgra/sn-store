<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TransactionController extends Controller
{
    /**
     * Tampilkan form tambah transaksi.
     */
    public function create(): View
    {
        return view('pages.admin.transactions.create', [
            'statuses' => ['pending' => 'Menunggu', 'paid' => 'Dibayar', 'shipped' => 'Dikirim', 'cancelled' => 'Dibatalkan'],
        ]);
    }
}
