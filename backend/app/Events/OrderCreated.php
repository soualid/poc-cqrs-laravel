<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $id,
        public readonly string $customerName,
        public readonly string $product,
        public readonly int $quantity,
        public readonly float $unitPrice,
    ) {}
}
