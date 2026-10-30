<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MidtransNotificationRequest;
use App\Services\PaymentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class MidtransNotificationController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Terima notifikasi pembayaran dari Midtrans.
     */
    public function __invoke(MidtransNotificationRequest $request): JsonResponse
    {
        try {
            $this->paymentService->handleNotification($request->validated());
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        return response()->json(['message' => 'OK']);
    }
}
