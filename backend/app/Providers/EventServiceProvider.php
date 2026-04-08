<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\ProjectOrder;
use App\Listeners\StoreOrder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            StoreOrder::class,
            ProjectOrder::class,
        ],
    ];
}
