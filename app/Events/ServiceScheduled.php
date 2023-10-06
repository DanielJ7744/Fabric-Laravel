<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The service that was run.
     *
     * @var array
     */
    public array $service;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $service)
    {
        $this->service = $service;
    }
}
