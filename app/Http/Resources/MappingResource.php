<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MappingResource extends JsonResource
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
            'username' => $this->username,
            'search_field' => $this->search_field,
            'mapping_name' => $this->mapping_name,
            'overrides' => $this->overrides ?? null,
            'created_at' => $this->created_at,
            'content' => $this->content,
        ];
    }
}
