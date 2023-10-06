<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultPayloadResource extends JsonResource
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
            'factory_system_schema_id' => $this->factory_system_schema_id,
            'type' => $this->type,
            'payload' => $this->payload,
            'factory_system_schema' => new FactorySystemSchemaResource($this->whenLoaded('factorySystemSchema')),
        ];
    }
}
