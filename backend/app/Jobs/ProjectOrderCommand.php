<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProjectOrderCommand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $id,
        public readonly string $customerName,
        public readonly string $product,
        public readonly int $quantity,
        public readonly float $unitPrice,
        public readonly string $status,
    ) {}

    public function handle(): void
    {
        DB::connection('read')->table('orders')->updateOrInsert(
            ['id' => $this->id],
            [
                'customer_name' => $this->customerName,
                'product' => $this->product,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice,
                'total' => round($this->quantity * $this->unitPrice, 2),
                'status' => $this->status,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
