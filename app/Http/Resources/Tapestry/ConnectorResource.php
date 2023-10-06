<?php

namespace App\Http\Resources\Tapestry;

use App\Models\Fabric\System;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ConnectorResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        if ($resource instanceof MissingValue) {
            parent::__construct($resource);

            return;
        }

        $systems = Cache::remember('connector.resource.systems', now()->addHour(), fn () => System::all()->keyBy('factory_name'));
        $this->system = $systems[$resource->system_chain] ?? null;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $credentials = is_array($this->extra) && !is_null($this->system) ? $this->fabricFormat(true) : null;

        if (is_null($credentials)) {
            return null;
        }

        return [
            'id' => $this->id,
            'environment' => $this->common_ref,
            'authorisation_type' => $this->authType(),
            'connector_name' => Arr::get($credentials, 'connector_name'),
            'timeZone' => Arr::get($credentials, 'timezone'),
            'dateFormat' => Arr::get($credentials, 'date_format'),
            'credentials' => $credentials,
            'system' => $this->system,
            'integration' => $this->getIntegration(),
        ];
    }
}
