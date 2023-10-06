<?php

namespace App\Http\Resources;

use App\Http\Resources\EventLogResource;
use App\Http\Resources\IntegrationResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_website' => $this->company_website,
            'company_phone' => $this->company_phone,
            'company_email' => $this->company_email,
            'active' => $this->active,
            'trial_ends_at' => $this->trial_ends_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'event_logs' => EventLogResource::collection($this->whenLoaded('eventLogs')),
            'integrations' => IntegrationResource::collection($this->whenLoaded('integrations')),
            'subscriptions' => SubscriptionResource::collection($this->whenLoaded('subscriptions')),
        ];
    }
}
