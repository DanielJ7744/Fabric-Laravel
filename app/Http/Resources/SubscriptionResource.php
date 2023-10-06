<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'upgrade' => $this->upgrade,
            'services' => $this->services,
            'transactions' => $this->transactions,
            'business_insights' => $this->business_insights,
            'api_keys' => $this->api_keys,
            'sftp' => $this->sftp,
            'users' => $this->users,
            'price' => $this->price,
            'sku' => $this->sku,
            'product_type' => $this->product_type,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
