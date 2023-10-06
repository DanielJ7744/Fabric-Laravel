<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'key' => $this->key,
            'schema_values' => json_decode($this->schema_values ?? '', true),
            'system_id' => $this->system_id,
            'system' => new SystemResource($this->whenLoaded('system')),
        ];
    }
}
