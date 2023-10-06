<?php

namespace App\Http\Resources;

use App\Http\Resources\OauthClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InboundEndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'integration_id' => $this->integration_id,
            'service_id' => $this->service_id,
            'slug' => $this->slug,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'clients' => OauthClientResource::collection($this->whenLoaded('clients')),
        ];
    }
}
