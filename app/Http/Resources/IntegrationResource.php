<?php

namespace App\Http\Resources;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\Tapestry\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrationResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'company_id' => $this->company_id,
            'username' => $this->username,
            'server' => $this->server,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'children' => self::collection($this->whenLoaded('children')),
            'factory_system_schemas' => FactorySystemSchemaResource::collection($this->whenLoaded('factorySystemSchemas')),
            'factory_systems' => FactorySystemResource::collection($this->whenLoaded('factorySystems')),
        ];
    }
}
