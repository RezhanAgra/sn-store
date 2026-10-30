<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGateway
{
    /**
     * Buat sesi pembayaran untuk pesanan dan kembalikan URL halaman pembayarannya.
     */
    public function createPayment(Order $order): string;

    /**
     * Pastikan notifikasi pembayaran benar-benar berasal dari payment gateway.
     *
     * @param  array<string, mixed>  $payload
     */
    public function isValidNotification(array $payload): bool;

    /**
     * Ambil status transaksi terbaru dari payment gateway.
     *
     * @return array<string, mixed>
     */
    public function fetchStatus(Order $order): array;
}
