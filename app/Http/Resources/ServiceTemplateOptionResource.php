<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTemplateOptionResource extends JsonResource
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
            'service_option_id' => $this->service_option_id,
            'service_template_id' => $this->service_template_id,
            'target' => $this->target,
            'value' => $this->value,
            'user_configurable' => $this->user_configurable,
            'service_option' => new ServiceOptionResource(optional($this->serviceOption)),
        ];
    }
}
