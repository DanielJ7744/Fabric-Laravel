<?php

namespace App\Listeners;

use App\Events\SystemWasCreated;
use App\Jobs\GenerateFactorySystems as GenerateFactorySystemsJob;

class GenerateFactorySystems
{
    /**
     * Handle the system created event
     *
     * @param SystemWasCreated $event
     *
     * @return void
     */
    public function handle(SystemWasCreated $event): void
    {
        GenerateFactorySystemsJob::dispatch($event->system);
    }
}
