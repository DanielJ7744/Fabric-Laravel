<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactorySystemSchemaResource extends JsonResource
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
            'id'                => $this->id,
            'factory_system_id' => $this->factory_system_id,
            'integration_id'    => $this->integration_id,
            'type'              => $this->type,
            'schema'            => $this->schema,
            'factory_system'    => new FactorySystemResource($this->whenLoaded('factorySystem')),
            'integration'       => new IntegrationResource($this->whenLoaded('integration')),
            'default_payload'   => new DefaultPayloadResource($this->whenLoaded('defaultPayload')),
            'original_type'     => $this->original_type,
            'original_schema'   => $this->original_schema,
        ];
    }
}
