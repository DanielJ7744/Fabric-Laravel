<?php

namespace App\Http\Resources;

use App\Http\Resources\AlertRecipientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertGroupResource extends JsonResource
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
            'company_id' => $this->company_id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'recipients' => AlertRecipientResource::collection($this->whenLoaded('recipients')),
        ];
    }
}
