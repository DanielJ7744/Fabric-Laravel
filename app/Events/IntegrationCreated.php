<?php

namespace App\Events;

use App\Models\Fabric\Integration;
use Illuminate\Queue\SerializesModels;

class IntegrationCreated
{
    use SerializesModels;

    /**
     * The service that was run.
     *
     * @var Integration
     */
    public Integration $integration;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Integration $integration)
    {
        $this->integration = $integration;
    }
}
