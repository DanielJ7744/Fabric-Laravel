<?php

namespace App\Events;

use App\Models\Fabric\System;
use Illuminate\Queue\SerializesModels;

class SystemWasCreated
{
    use SerializesModels;

    /**
     * The system that was created.
     *
     * @var System
     */
    public System $system;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }
}
