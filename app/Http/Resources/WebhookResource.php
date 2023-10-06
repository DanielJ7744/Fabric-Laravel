<?php

namespace App\Http\Resources;

use App\Http\Resources\Tapestry\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'status' => $this->active ? 'Enabled' : 'Disabled',
            'integration_id' => $this->integration_id,
            'integration' => new IntegrationResource($this->whenLoaded('integration')),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'remote_reference' => $this->remote_reference,
            'event_type_id' => $this->event_type_id,
            'event_type' => new EventTypeResource($this->whenLoaded('eventType')),
        ];
    }
}
