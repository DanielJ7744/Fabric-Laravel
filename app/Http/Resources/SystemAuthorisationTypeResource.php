<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemAuthorisationTypeResource extends JsonResource
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
            'id'                    => $this->id,
            'credentials_schema'    => $this->credentials_schema,
            'system'                => new SystemResource($this->whenLoaded('system')),
            'authorisation_type'    => new AuthorisationTypeResource($this->whenLoaded('authorisationType')),
        ];
    }
}
