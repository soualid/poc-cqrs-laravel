<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CreateOrderCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderCommandController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0.01',
        ]);

        $id = Str::uuid()->toString();

        CreateOrderCommand::dispatch(
            id: $id,
            customerName: $validated['customer_name'],
            product: $validated['product'],
            quantity: $validated['quantity'],
            unitPrice: $validated['unit_price'],
        )->onQueue('commands');

        return response()->json([
            'message' => 'Order command dispatched',
            'id' => $id,
        ], 202);
    }
}
