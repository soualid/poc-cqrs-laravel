<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateOrderCommand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $id,
        public readonly string $customerName,
        public readonly string $product,
        public readonly int $quantity,
        public readonly float $unitPrice,
    ) {}

    public function handle(): void
    {
        DB::connection('write')->table('orders')->insert([
            'id' => $this->id,
            'customer_name' => $this->customerName,
            'product' => $this->product,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ProjectOrderCommand::dispatch(
            id: $this->id,
            customerName: $this->customerName,
            product: $this->product,
            quantity: $this->quantity,
            unitPrice: $this->unitPrice,
            status: 'confirmed',
        )->onQueue('projections');
    }
}
