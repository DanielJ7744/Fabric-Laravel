<?php

namespace App\Http\Resources;

use App\Http\Resources\Tapestry\ConnectorResource;
use App\Models\Tapestry\Connector;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventLogResource extends JsonResource
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
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'audit_id' => $this->audit_id,
            'area' => $this->area,
            'action' => $this->action,
            'value' => $this->value,
            'method' => $this->method,
            'successful' => $this->successful,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'model' => $this->loadModel(),
            'user' => new UserResource($this->whenLoaded('user')),
            'audit' => new AuditResource($this->whenLoaded('audit')),
        ];
    }

    protected function loadModel()
    {
        return $this->getAttribute('model_type') === Connector::class
            ? $this->loadConnector()
            : $this->mergeWhen($this->model, $this->model);
    }

    protected function loadConnector(): ?ConnectorResource
    {
        [$username, $id] = explode('|', $this->getAttribute('model_id'));
        $connector = (new Connector())->setIdxTable($username)->withTrashed()->find($id);

        return $connector ? ConnectorResource::make($connector) : null;
    }
}
