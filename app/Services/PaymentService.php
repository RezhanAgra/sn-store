<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Services\Payment\PaymentGateway;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentService
{
    public function __construct(private PaymentGateway $paymentGateway) {}

    /**
     * Minta URL pembayaran ke gateway. Jika gagal, pesanan tetap pending dan bisa dicoba lagi.
     */
    public function preparePayment(Order $order): Order
    {
        try {
            $order->update(['payment_url' => $this->paymentGateway->createPayment($order)]);
        } catch (Throwable $e) {
            report($e);
        }

        return $order;
    }

    /**
     * Proses notifikasi dari gateway setelah memverifikasi signature-nya.
     *
     * @param  array<string, mixed>  $payload
     *
     * @throws AuthorizationException
     */
    public function handleNotification(array $payload): void
    {
        if (! $this->paymentGateway->isValidNotification($payload)) {
            throw new AuthorizationException('Signature notifikasi tidak valid.');
        }

        $order = Order::where('code', $payload['order_id'])->firstOrFail();

        $this->applyStatus($order, $payload);
    }

    /**
     * Sinkronkan status pesanan pending langsung dari gateway (cadangan jika notifikasi belum masuk).
     */
    public function syncStatus(Order $order): Order
    {
        if ($order->status !== OrderStatus::Pending || ! $order->payment_url) {
            return $order;
        }

        try {
            $this->applyStatus($order, $this->paymentGateway->fetchStatus($order));
        } catch (Throwable $e) {
            report($e);
        }

        return $order;
    }

    /**
     * Terapkan status pembayaran. Hanya pesanan pending yang bisa berubah; stok dikembalikan jika dibatalkan.
     *
     * @param  array<string, mixed>  $payload
     */
    private function applyStatus(Order $order, array $payload): void
    {
        $newStatus = $this->resolveStatus($payload);

        DB::transaction(function () use ($order, $newStatus): void {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($newStatus === null || $newStatus === OrderStatus::Pending || $locked->status !== OrderStatus::Pending) {
                return;
            }

            if ($newStatus === OrderStatus::Cancelled) {
                $this->restoreStock($locked);
            }

            $locked->update(['status' => $newStatus]);
        });

        $order->refresh();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveStatus(array $payload): ?OrderStatus
    {
        return match ($payload['transaction_status'] ?? null) {
            'settlement' => OrderStatus::Paid,
            'capture' => ($payload['fraud_status'] ?? 'accept') === 'accept' ? OrderStatus::Paid : OrderStatus::Pending,
            'pending' => OrderStatus::Pending,
            'deny', 'cancel', 'expire', 'failure' => OrderStatus::Cancelled,
            default => null,
        };
    }

    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)->increment('stock', $item->quantity);
            }
        }
    }
}
