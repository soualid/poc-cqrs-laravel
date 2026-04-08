<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class StoreOrder implements ShouldQueue
{
    public string $queue = 'commands';

    public function handle(OrderCreated $event): void
    {
        DB::connection('write')->table('orders')->insert([
            'id' => $event->id,
            'customer_name' => $event->customerName,
            'product' => $event->product,
            'quantity' => $event->quantity,
            'unit_price' => $event->unitPrice,
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
