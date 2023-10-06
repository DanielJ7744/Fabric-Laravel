<?php

namespace App\Http\Resources\Tapestry;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Fabric\Entity;
use App\Models\Fabric\System;
use App\Http\Helpers\ServiceHelper;
use App\Models\Fabric\FilterTemplate;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\EntityResource;
use App\Http\Resources\SystemResource;
use App\Http\Helpers\ServiceFilterHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceLogResource extends JsonResource
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
            'service_id' => $this->service_id,
            'from_factory' => $this->from_factory,
            'from_environment' => $this->from_environment,
            'to_factory' => $this->to_factory,
            'to_environment' => $this->to_environment,
            'username' => $this->username,
            'requested_by' => $this->requested_by,
            'status' => $this->status,
            'notes' => $this->notes,
            'runtime' => $this->runtime,
            'current_page' => $this->current_page,
            'total_pages' => $this->total_pages,
            'total_count' => $this->total_count,
            'page_size' => $this->page_size,
            'last_page_time' => $this->last_page_time,
            'error' => $this->error,
            'warning' => $this->warning,
            'other' => $this->other,
            'filters' => $this->filters,
            'due_at' => $this->due_at,
            'queued_at' => $this->queued_at,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'reported_at' => $this->reported_at,
            'service' => new ServiceResource($this->whenLoaded('service')),
        ];
    }
}
