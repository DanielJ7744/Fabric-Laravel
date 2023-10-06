<?php

namespace App\Http\Resources\Tapestry;

use App\Http\Helpers\ServiceFilterHelper;
use App\Http\Resources\AlertConfigResource;
use App\Http\Resources\EntityResource;
use App\Http\Resources\FactorySystemResource;
use App\Http\Resources\IntegrationResource;
use App\Http\Resources\ServiceTemplateResource;
use App\Http\Resources\SystemResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class ServiceResource extends JsonResource
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

        if ($resource->from_factory) {
            $this->source_entity = $resource->getSourceEntity();
            $this->source_system = $resource->getSourceSystem();
            $this->source_connector = $resource->sourceConnector();
            $this->source_filter_template = $resource->getFilterTemplate();
        }

        if ($resource->to_factory) {
            $this->destination_entity = $resource->getDestinationEntity();
            $this->destination_system = $resource->getDestinationSystem();
            $this->destination_connector = $resource->destinationConnector();
        }


        try {
            $this->filters = ServiceFilterHelper::constructFilters(
                $resource->getSourceFactorySystem(),
                ServiceFilterHelper::getFromOptionFilters($resource->from_options)
            );
        } catch (\Throwable $th) {
            $this->filters = null;
        }

        parent::__construct($resource);
    }

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
            'run_id' => $this->run_id,
            'status' => $this->status,
            'description' => $this->description,
            'from_factory' => $this->from_factory,
            'from_environment' => $this->from_environment,
            'to_factory' => $this->to_factory,
            'to_environment' => $this->to_environment,
            'username' => $this->username,
            'schedule' => $this->schedule,
            'timeout' => $this->timeout,
            'from_options' => $this->from_options,
            'from_mapping' => $this->from_mapping,
            'to_options' => $this->to_options,
            'to_mapping' => $this->to_mapping,
            'idle_timeout' => $this->idle_timeout,
            'run_count' => $this->run_count,
            'filters' => $this->filters,
            'billable' => $this->billable,
            'dashboard_visibility' => $this->dashboard_visibility,
            'filterable' => $this->filterable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'source_connector' => !is_null($this->source_connector) ? new ConnectorResource($this->source_connector) : null,
            'destination_connector' => !is_null($this->destination_connector) ? new ConnectorResource($this->destination_connector) : null,
            'source' => sprintf('%s %s', optional($this->source_system)->name, optional($this->source_entity)->name),
            'destination' => sprintf('%s %s', optional($this->destination_system)->name, optional($this->destination_entity)->name),
            'integration' => new IntegrationResource($this->whenLoaded('integration')),
            'integration_id' => optional($this->integration)->getKey(),
            'config' => new AlertConfigResource($this->whenLoaded('alertConfigs')),
            'service_template' => new ServiceTemplateResource($this->whenLoaded('serviceTemplate')),
            'source_factory_system' => $this->from_factory ? (new FactorySystemResource($this->getSourceFactorySystem())) : null,
            'destination_factory_system' => $this->to_factory ? (new FactorySystemResource($this->getDestinationFactorySystem())) : null,
            'source_filter_template' => $this->source_filter_template,
            'source_filter_template_id' => optional($this->source_filter_template)->getKey(),
            'source_entity' => $this->source_entity ? new EntityResource($this->source_entity) : null,
            'source_entity_id' => optional($this->source_entity)->getKey(),
            'destination_entity' => $this->destination_entity ? new EntityResource($this->destination_entity) : null,
            'destination_entity_id' => optional($this->destination_entity)->getKey(),
            'source_system' => new SystemResource($this->source_system),
            'source_system_id' => optional($this->source_system)->getKey(),
            'destination_system' => new SystemResource($this->destination_system),
            'destination_system_id' => optional($this->destination_system)->getKey(),
        ];
    }
}
