<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterFieldResource extends JsonResource
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
            'key' => $this->key,
            'factory_system_id' => $this->factory_system_id,
            'factory_system' => new FactorySystemResource($this->whenLoaded('factorySystem')),
            'filter_types' => FilterTypeResource::collection($this->whenLoaded('filterType')),
            'filter_operators' => FilterOperatorResource::collection($this->whenLoaded('filterOperator')),
            'default' => $this->default,
            'default_value' => $this->default_value,
            'default_type_id' => $this->default_type_id,
            'default_type' => new FilterTypeResource($this->whenLoaded('defaultType')),
            'default_operator_id' => $this->default_operator_id,
            'default_operator' => new FilterOperatorResource($this->whenLoaded('defaultOperator')),
        ];
    }
}
