<?php

namespace App\Rules;

use App\Models\Fabric\EventType;
use App\Models\Tapestry\Service;
use Illuminate\Contracts\Validation\Rule;

class ServiceSourceHasEventType implements Rule
{
    private Service $service;

    public function __construct(int $serviceId)
    {
        $this->service = Service::find($serviceId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $eventType = EventType::find($value);

        return $eventType->system->factory_name === $this->service->getSourceSystem()->factory_name;
    }

    public function message(): string
    {
        return "This service's source system must support the specified event type.";
    }
}
