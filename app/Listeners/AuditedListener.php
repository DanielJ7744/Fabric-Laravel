<?php

namespace App\Listeners;

use App\Http\Interfaces\EventLogInterface;
use App\Models\Fabric\EventLog;
use OwenIt\Auditing\Events\Audited;

class AuditedListener
{
    /**
     * Handle the Audited event.
     *
     * @param Audited $event
     * @return void
     */
    public function handle(Audited $event)
    {
        if (!$event->model instanceof EventLogInterface) {
            return;
        }

        EventLog::make()
            ->setUser(auth()->user())
            ->setArea($event->model->getArea())
            ->setAction($this->getAction($event))
            ->audit()->associate($event->audit)
            ->setAttribute('model_id', $event->model->getAttribute('id'))
            ->setAttribute('model_type', get_class($event->model))
            ->save();
    }

    private function getAction(Audited $event): string
    {
        return sprintf('%s_%s', strtolower(class_basename($event->model)), $event->audit->event);
    }
}
