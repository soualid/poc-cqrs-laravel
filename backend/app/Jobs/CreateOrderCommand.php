<?php

namespace App\Jobs;

use App\Events\OrderCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        OrderCreated::dispatch(
            id: $this->id,
            customerName: $this->customerName,
            product: $this->product,
            quantity: $this->quantity,
            unitPrice: $this->unitPrice,
        );
    }
}
