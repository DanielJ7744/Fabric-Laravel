<?php

namespace App\Http\Resources\Tapestry;

use App\Http\Resources\IntegrationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMapResource extends JsonResource
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
            'methods' => $this->methods,
            'fallback' => $this->fallback
        ];
    }
}
