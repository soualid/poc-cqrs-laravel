<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ProjectOrder implements ShouldQueue
{
    public string $queue = 'projections';

    public function handle(OrderCreated $event): void
    {
        DB::connection('read')->table('orders')->updateOrInsert(
            ['id' => $event->id],
            [
                'customer_name' => $event->customerName,
                'product' => $event->product,
                'quantity' => $event->quantity,
                'unit_price' => $event->unitPrice,
                'total' => round($event->quantity * $event->unitPrice, 2),
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
