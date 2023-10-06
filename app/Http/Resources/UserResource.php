<?php

namespace App\Http\Resources;

use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'telephone' => $this->telephone,
            'mobile' => $this->mobile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'integrations' => IntegrationResource::collection($this->whenLoaded('integrations')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
