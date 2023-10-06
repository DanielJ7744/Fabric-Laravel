<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertConfigResource extends JsonResource
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
            'service_id' => $this->service_id,
            'throttle_value' => $this->throttle_value,
            'error_alert_status' => $this->error_alert_status,
            'error_alert_threshold' => $this->error_alert_threshold,
            'frequency_alert_status' => $this->frequency_alert_status,
            'frequency_alert_threshold' => $this->frequency_alert_threshold,
            'alert_frequency' => $this->alert_frequency,
            'alert_status' => $this->alert_status,
        ];
    }
}
