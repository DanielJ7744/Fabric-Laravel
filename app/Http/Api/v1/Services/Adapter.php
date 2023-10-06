<?php

namespace App\Http\Api\v1\Services;

use App\Http\Helpers\ErrorHelper;
use App\Http\Helpers\IntegrationHelper;
use App\Http\Helpers\ServiceFilterHelper;
use App\Http\Helpers\ServiceHelper;
use App\Models\Fabric\Entity;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Service;
use App\Models\Fabric\System;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class Adapter extends AbstractResourceAdapter
{
    private IntegrationHelper $integrationHelper;

    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @inheritDoc
     */
    protected function createRecord(ResourceObject $resource): Service
    {
        $attributes = $resource->getAttributes();
        $integration = Auth::user()->company
            ->integrations()
            ->where('id', Arr::get($attributes, 'sourceIntegrationSystem.relationships.integration.data.id'))
            ->first();
        $server = $integration->server;
        $username = $integration->username;

        $sourceSystem = System::where(
            'id',
            Arr::get($attributes, 'sourceIntegrationSystem.relationships.system.data.id')
        )->first();
        $destinationSystem = System::where(
            'id',
            Arr::get($attributes, 'destinationIntegrationSystem.relationships.system.data.id')
        )->first();

        $entity = Entity::where('id', Arr::get($attributes, 'entity.id'))->first();

        $data = [
            'status' => 0,
            'description' => Arr::get($attributes, 'serviceDetails.description'),
            'from_factory' => ServiceHelper::getBaseFactoryString($sourceSystem->factory_name, 'Pull', $entity->factory_name),
            'from_environment' => Arr::get($attributes, 'sourceIntegrationSystem.attributes.environment'),
            'to_factory' => ServiceHelper::getBaseFactoryString($destinationSystem->factory_name, 'Push', $entity->factory_name),
            'to_environment' => Arr::get($attributes, 'destinationIntegrationSystem.attributes.environment'),
            'schedule' => Arr::get($attributes, 'serviceDetails.schedule'),
            'from_mapping' => null,
            'to_mapping' => null,
            'from_options' => json_encode([
                'timezone' => Arr::get($attributes, 'serviceDetails.fromOptions.timezone'),
                'date_format' => Arr::get($attributes, 'serviceDetails.fromOptions.dateFormat'),
                'page_size' => Arr::get($attributes, 'serviceDetails.pageSize'),
                'attempt_count' => Arr::get($attributes, 'serviceDetails.maxRetries'),
            ], JSON_PRETTY_PRINT),
            'to_options' => json_encode([
                'timezone' => Arr::get($attributes, 'serviceDetails.toOptions.timezone'),
                'date_format' => Arr::get($attributes, 'serviceDetails.toOptions.dateFormat'),
            ], JSON_PRETTY_PRINT),
        ];

        $result = $this->integrationHelper->createService($server, $username, ['fields' => $data]);
        if (!$result) {
            throw new Exception('Failed to create service');
        }

        $data['id'] = $result->first();

        return new Service($data, $integration, $entity, $sourceSystem, null, $entity, $destinationSystem);
    }

    public function update($record, array $document, EncodingParametersInterface $parameters): Service
    {
        $integration = $record->getIntegration();
        $server = $integration->server;
        $username = $integration->username;
        $serviceId = $record->getServiceId();
        $data = [];

        if (isset($document['data']['attributes'])) {
            foreach ($document['data']['attributes'] as $field => $value) {
                $setter = 'set' . Str::studly($field);
                $getter = 'get' . Str::studly($field);
                $record->$setter($value);
                $data[Str::snake($field)] = method_exists($record, $getter) ? $record->$getter($value) : $value;
            }
        }

        $this->integrationHelper->updateService($server, $username, $serviceId, $data);
        return $record;
    }

    /**
     * @inheritDoc
     */
    protected function fillAttributes($record, Collection $attributes)
    {
        // TODO: Implement fillAttributes() method.
    }

    /**
     * @inheritDoc
     */
    protected function persist($record)
    {
        // TODO: Implement persist() method.
    }

    /**
     * @inheritDoc
     */
    protected function destroy($record)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @inheritDoc
     */
    public function query(EncodingParametersInterface $parameters)
    {
        $parameters = $parameters->toArray();
        $company = Auth::user()->company;

        if (!$company) {
            return [];
        }

        $integrations = isset($parameters['integration_ids'])
            ? $company->integrations->whereIn('id', $parameters['integration_ids'])
            : $company->integrations;
        unset($parameters['integration_ids']);
        $errors = new Collection();

        return $integrations->flatMap(function (Integration $integration) use ($errors, $parameters) {
            try {
                $servicesData = is_array($parameters) && !empty($parameters)
                    ? $this->integrationHelper->getFilteredServices($integration['server'], $integration['username'], $parameters)
                    : $this->integrationHelper->getServices($integration['server'], $integration['username']);

                return $servicesData->transform(function (array $serviceData) use ($integration) {
                    $fromEntity = ServiceHelper::getEntityForService($serviceData, 'from');
                    $fromSystem = ServiceHelper::getSystemForService($serviceData, 'from');
                    $fromFilterTemplate = null;

                    if ($fromEntity && $fromSystem) {
                        $fromFilterTemplate = FilterTemplate::where('entity_id', $fromEntity->id)->where('system_id', $fromSystem->id)->first();
                    }

                    $toEntity = ServiceHelper::getEntityForService($serviceData, 'to');
                    $toSystem = ServiceHelper::getSystemForService($serviceData, 'to');

                    try {
                        if (!$fromSystem || !$fromEntity) {
                            throw new Exception('Unable to construct service filters, invalid data');
                        }

                        if (!is_string($serviceData['from_options'])) {
                            throw new Exception('from_options is not a string');
                        }

                        $filters = ServiceFilterHelper::constructFilters(
                            $fromSystem,
                            $fromEntity,
                            ServiceFilterHelper::getFromOptionFilters($serviceData['from_options'])
                        );
                    } catch (Exception $exception) {
                        $filters = null;
                    }

                    return new Service(
                        $serviceData,
                        $integration,
                        $fromEntity,
                        $fromSystem,
                        $fromFilterTemplate,
                        $toEntity,
                        $toSystem,
                        $filters
                    );
                });
            } catch (Exception $exception) {
                $error = sprintf(
                    'Failed to get services for integration %s. Error: %s',
                    $integration->name,
                    $exception->getMessage()
                );
                Log::error($error);
                $errors->push($error);
                return [];
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function exists($resourceId)
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function find($resourceId): ?Service
    {
        $ids = explode('|', $resourceId);

        if (count($ids) !== 2) {
            throw new JsonApiException(ErrorHelper::create(
                'Failed to get service',
                'The id must be the integration username and the service id separated by pipe. E.g. custom_peaks|35.'
            ));
        }

        $integrationUsername = $ids[0];
        $serviceId = $ids[1];
        $integration = Integration::firstWhere(['username' => $integrationUsername]);

        if (!$integration) {
            throw new JsonApiException(ErrorHelper::create(
                'Failed to get integration',
                sprintf('Failed to get integration %s', $integrationUsername)
            ));
        }

        try {
            $serviceData = $this->integrationHelper->getService($integration->server, $integration->username, $serviceId);
        } catch (Exception $exception) {
            if ($exception instanceof ClientException && $exception->getResponse()->getStatusCode() === 404) {
                return null;
            }

            $error = new Error(null, null, 500, null, 'Failed to get service', sprintf("Failed to get service %s\n%s", $resourceId, $exception->getMessage()));
            throw new JsonApiException($error, 500);
        }

        $fromEntity = ServiceHelper::getEntityForService($serviceData, 'from');
        $fromSystem = ServiceHelper::getSystemForServiceUsingFactoryName($serviceData, 'from');
        $toEntity = ServiceHelper::getEntityForService($serviceData, 'to');
        $toSystem = ServiceHelper::getSystemForServiceUsingFactoryName($serviceData, 'to');
        $fromFilterTemplate = null;
        try {
            $filters = ServiceFilterHelper::constructFilters(
                $fromSystem,
                $fromEntity,
                ServiceFilterHelper::getFromOptionFilters($serviceData['from_options'])
            );
        } catch (Exception $exception) {
            $filters = null;
        }

        if ($fromEntity && $fromSystem) {
            $fromFilterTemplate = FilterTemplate::firstWhere(['entity_id' => $fromEntity->id, 'system_id' => $fromSystem->id]);
        }

        return new Service(
            $serviceData,
            $integration,
            $fromEntity,
            $fromSystem,
            $fromFilterTemplate,
            $toEntity,
            $toSystem,
            $filters
        );
    }

    /**
     * @inheritDoc
     */
    public function findMany(array $resourceIds)
    {
        // TODO: Implement findMany() method.
    }
}
