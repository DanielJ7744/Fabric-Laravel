<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactorySystemResource extends JsonResource
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
            'factory' => new FactoryResource($this->whenLoaded('factory')),
            'system' => new SystemResource($this->whenLoaded('system')),
            'entity' => new EntityResource($this->whenLoaded('entity')),
            'schemas' => FactorySystemSchemaResource::collection($this->whenLoaded('schemas')),
            'service_options' => ServiceOptionResource::collection($this->whenLoaded('serviceOption')),
            'direction' => $this->direction,
            'default_map_name' => $this->default_map_name,
            'integration' => new IntegrationResource($this->whenLoaded('integration')),
            'integration_id' => $this->integration_id,
            'display_name' => $this->display_name,
        ];
    }
}
