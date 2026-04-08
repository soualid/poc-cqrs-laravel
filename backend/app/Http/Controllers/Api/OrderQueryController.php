<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderQueryController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = DB::connection('read')
            ->table('orders')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($orders);
    }

    public function show(string $id): JsonResponse
    {
        $order = DB::connection('read')
            ->table('orders')
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
}
