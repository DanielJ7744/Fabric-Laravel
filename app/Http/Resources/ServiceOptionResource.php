<?php

namespace App\Http\Resources;

use App\Models\Fabric\FactorySystemServiceOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'factory_system_service_option' => new FactorySystemServiceOptionResource($this->whenPivotLoaded(new FactorySystemServiceOption, fn () => $this->pivot)),
            'target' => $this->whenPivotLoaded('service_option_service_template', function () {
                return $this->pivot->target;
            }),
            'value' => $this->whenPivotLoaded('service_option_service_template', function () {
                return $this->pivot->value;
            }),
            'user_configurable' => $this->whenPivotLoaded('service_option_service_template', function () {
                return $this->pivot->user_configurable;
            })
        ];
    }
}
