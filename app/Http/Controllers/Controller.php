<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{

    public function success(mixed $data, string $message = "okay", int $statusCode = 200): JsonResponse
    {
        return response()->json([
            "data" => $data,
            "success" => true,
            "message" => $message
        ], $statusCode);
    }

    public function error(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            "data" => null,
            "success" => false,
            "message" => $message
        ]);
    }
}