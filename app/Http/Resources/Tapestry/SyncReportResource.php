<?php

namespace App\Http\Resources\Tapestry;

use App\Http\Resources\IntegrationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyncReportResource extends JsonResource
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
            'type' => $this->type,
            'system_chain' => $this->system_chain,
            'source_id' => $this->source_id,
            'common_ref' => $this->common_ref,
            'status' => $this->status,
            'first_run_id' => $this->first_run_id,
            'last_run_id' => $this->last_run_id,
            'message' => $this->message,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'integration' => new IntegrationResource($this->whenLoaded('integration')),
            'first_service_id' => $this->first_service_id,
            'resync_values' => $this->resync_values ?? null
        ];
    }
}
