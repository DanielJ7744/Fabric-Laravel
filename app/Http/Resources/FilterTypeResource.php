<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterTypeResource extends JsonResource
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
            'placeholder' => $this->placeholder,
            'key' => $this->key,
            'filter_operators' => FilterOperatorResource::collection($this->whenLoaded('filterOperator')),
        ];
    }
}
