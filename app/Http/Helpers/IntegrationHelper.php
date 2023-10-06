<?php

namespace App\Http\Helpers;

use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\Integration;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class IntegrationHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getServices(string $server, string $username): ?Collection
    {
        $url = sprintf('%s/%s', ApiHelper::buildTapestryApiPath($server, 'services'), $username);

        return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['data']);
    }

    public function getFilteredServices(string $server, string $username, array $filters = []): ?Collection
    {
        $url = sprintf(
            '%s/%s/filter?%s',
            ApiHelper::buildTapestryApiPath($server, 'services'),
            $username,
            ApiHelper::buildTapestryQuery($filters)
        );

        return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['data']);
    }

    public function getService(string $server, string $username, int $id): array
    {
        $url = sprintf('%s/%s/%s', ApiHelper::buildTapestryApiPath($server, 'services'), $username, $id);

        return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['data'])->toArray();
    }

    public function scheduleService(string $server, string $username, int $id, array $filters = []): ?Collection
    {
        $url = sprintf(
            '%s/%s/run/%s%s',
            ApiHelper::buildTapestryApiPath($server, 'services'),
            $username,
            $id,
            empty($filters) ? '' : '/targeted'
        );

        return collect(json_decode($this->client->post($url, ['json' => $filters])->getBody()->getContents(), true)['data']);
    }

    public function updateService(string $server, string $username, int $id, array $data): void
    {
        $url = sprintf('%s/%s/%s', ApiHelper::buildTapestryApiPath($server, 'services'), $username, $id);
        $this->client->put($url, ['json' => $data]);
    }

    public function createService(string $server, string $username, array $data): ?array
    {
        $url = sprintf('%s/%s', ApiHelper::buildTapestryApiPath($server, 'services'), $username);

        $result = json_decode($this->client->post($url, ['json' => $data])->getBody()->getContents(), true)['data'];

        return !is_array($result) ? ['id' => $result] : $result;
    }

    public function getServiceLog(string $server, string $username, array $filters = []): ?array
    {
        $url = sprintf('%s/%s', ApiHelper::buildTapestryApiPath($server, 'servicelogs'), $username);

        return collect(json_decode($this->client->get($url, $filters)->getBody()->getContents(), true)['data'])->first();
    }

    public function getFilteredServiceLog(string $server, string $username, array $filters = []): ?Collection
    {
        $url = sprintf(
            '%s/%s?%s',
            ApiHelper::buildTapestryApiPath($server, 'servicelogs'),
            $username,
            ApiHelper::buildTapestryQuery($filters)
        );

        return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['data']);
    }

    public function getFilterTemplate(?int $filterTemplateId, Integration $integration, int $serviceId): ?FilterTemplate
    {
        if ($filterTemplateId !== null) {
            return FilterTemplate::find($filterTemplateId);
        }

        try {
            $service = $this->getService($integration->server, $integration->username, $serviceId);
        } catch (Exception $e) {
            throw new JsonApiException(ErrorHelper::create('Failed to get service', sprintf('Error: %s', $e->getMessage())));
        }

        $fromSystem = ServiceHelper::getSystemForService($service, 'from');
        $fromEntity = ServiceHelper::getEntityForService($service, 'from');
        $fromFactory = Factory::firstWhere('name', ServiceHelper::getEntityStringFromFactoryString($service['from_factory']));

        if ($fromSystem === null) {
            throw new JsonApiException(ErrorHelper::create('Failed to get system'));
        }

        if ($fromEntity === null) {
            throw new JsonApiException(ErrorHelper::create('Failed to get entity'));
        }

        if ($fromFactory === null) {
            throw new JsonApiException(ErrorHelper::create('Failed to get factory'));
        }

        $factorySystem = FactorySystem::where('direction', 'pull')
            ->where('system_id', $fromSystem->id)
            ->where('entity_id', $fromEntity->id)
            ->where('factory_id', $fromFactory->id)
            ->first();

        if ($factorySystem === null) {
            throw new JsonApiException(ErrorHelper::create('Failed to get factory system'));
        }

        return FilterTemplate::firstWhere(['factory_system_id' => $factorySystem->id]);
    }

    public function validateFilters(string $allowedDataType, array $filters): void
    {
        foreach ($filters as $filter) {
            if (gettype($filter) !== $allowedDataType) {
                throw new JsonApiException(ErrorHelper::create(
                    'Filters not in correct data type',
                    sprintf(
                        'Expected passed filters to be of type %s but (%s) does not match type',
                        $allowedDataType,
                        $filter
                    )
                ));
            }
        }
    }
}
