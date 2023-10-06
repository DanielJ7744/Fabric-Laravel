<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemResource extends JsonResource
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
            'system_type_id' => $this->system_type_id,
            'name' => $this->name,
            'factory_name' => $this->factory_name,
            'slug' => $this->slug,
            'website' => $this->website,
            'popular' => $this->popular,
            'description' => $this->description,
            'date_format' => $this->date_format,
            'time_zone' => $this->time_zone,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'entities' => EntityResource::collection($this->whenLoaded('entities')),
            'system_type' => new SystemTypeResource($this->whenLoaded('systemType')),
            'system_authorisation_types' => SystemAuthorisationTypeResource::collection($this->whenLoaded('systemAuthorisationTypes')),
            'documentation_link' => $this->documentation_link,
            'has_webhooks' => $this->has_webhooks,
            'webhook_schema' => $this->webhook_schema,
            'documentation_link_description' => $this->documentation_link_description,
            'environment_suffix_title' => $this->environment_suffix_title,
            'media_url' => $this->getMediaUrl()
        ];
    }
}
