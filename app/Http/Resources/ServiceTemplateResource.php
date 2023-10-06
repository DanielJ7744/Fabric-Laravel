<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTemplateResource extends JsonResource
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
            'name' => $this->name,
            'integration_id' => $this->integration_id,
			'enabled' => $this->enabled,
            'integration' => new IntegrationResource($this->whenLoaded('integration')),
            'source_factory_system' => new FactorySystemResource($this->whenLoaded('source')),
            'destination_factory_system' => new FactorySystemResource($this->whenLoaded('destination')),
            'service_template_options' => ServiceTemplateOptionResource::collection($this->whenLoaded('serviceTemplateOptions')),
        ];
    }
}
