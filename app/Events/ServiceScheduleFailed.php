<?php

namespace App\Events;

use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceScheduleFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The service that was run.
     *
     * @var array
     */
    public array $service;

    /**
     * The exception that occurred.
     *
     * @var string
     */
    public Exception $error;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $service, Exception $error)
    {
        $this->service = $service;
        $this->error = $error;
    }
}
