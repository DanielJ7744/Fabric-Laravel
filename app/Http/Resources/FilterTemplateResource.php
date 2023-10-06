<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterTemplateResource extends JsonResource
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
            'filter_key' => $this->filter_key,
            'template' => $this->template,
            'note' => $this->note,
            'pw_value_field' => $this->pw_value_field,
            'factory_system' => new FactorySystemResource($this->whenLoaded('factorySystem')),
        ];
    }
}
