<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'patchworks_role'   => (bool) $this->patchworks_role,
            'permissions'       => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
