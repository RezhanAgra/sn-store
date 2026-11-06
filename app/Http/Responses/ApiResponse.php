<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * Respons sukses dengan format: code, status, message, data.
     * Untuk resource berpaginasi, `meta` dan `links` ikut disertakan.
     */
    public static function success(mixed $data = null, string $message = 'Berhasil.', int $code = Response::HTTP_OK): JsonResponse
    {
        $extra = [];

        if ($data instanceof JsonResource) {
            $payload = $data->response()->getData(true);
            $data = $payload['data'] ?? null;
            $extra = array_intersect_key($payload, array_flip(['meta', 'links']));
        }

        return response()->json([
            'code' => $code,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ] + $extra, $code);
    }

    /**
     * Respons gagal dengan format: code, status, message, data (berisi detail error bila ada).
     */
    public static function error(string $message, int $code = Response::HTTP_BAD_REQUEST, mixed $data = null): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
