<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactorySystemServiceOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'factory_system_id' => $this->factory_system_id,
            'service_option_id' => $this->service_option_id,
            'value' => $this->value,
            'user_configurable' => $this->user_configurable,
            'properties' => $this->properties,
            'factory_system' => new ServiceOptionResource($this->whenLoaded('factorySystem')),
            'service_option' => new ServiceOptionResource($this->whenLoaded('serviceOption')),
        ];
    }
}
