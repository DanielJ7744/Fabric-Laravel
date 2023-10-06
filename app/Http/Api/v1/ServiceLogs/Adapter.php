<?php

namespace App\Http\Api\v1\ServiceLogs;

use App\Events\ServiceScheduleFailed;
use App\Events\ServiceScheduled;
use App\Http\Helpers\ErrorHelper;
use App\Http\Helpers\IntegrationHelper;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceLog;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
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
    protected function createRecord(ResourceObject $resource): ?ServiceLog
    {
        $attributes = $resource->getAttributes();
        $integrationUsername = $attributes['integrationUsername'] ?? null;
        $serviceId = $attributes['serviceId'] ?? null;
        $filterValues = $attributes['filterValues'] ?? null;
        $filterTemplateId = $attributes['filterTemplateId'] ?? null;

        if ($integrationUsername === null || $serviceId === null) {
            throw new JsonApiException(ErrorHelper::create('Failed to create service-log', 'Fields \'integrationId\' and \'serviceId\' are required'));
        }

        if (!is_integer($serviceId)) {
            throw new JsonApiException(ErrorHelper::create('Failed to create service-log', 'Field \'serviceId\' must be an integer'));
        }

        if ($filterValues !== null && !is_array($filterValues)) {
            throw new JsonApiException(ErrorHelper::create('Failed to create service-log', 'Field \'filterValues\' must be an array'));
        }

        $integration = Integration::firstWhere(['username' => $integrationUsername]);
        $service = $this->integrationHelper->getService($integration->server, $integration->username, $serviceId);

        if ($integration === null) {
            throw new JsonApiException(ErrorHelper::create(
                'Failed to get integration',
                sprintf('Failed to get integration %s', $integrationUsername)
            ));
        }

        $filters = [];

        if (!empty($filterValues)) {
            $filterTemplate = $this->integrationHelper->getFilterTemplate($filterTemplateId, $integration, $serviceId);

            if ($filterTemplate === null) {
                throw new JsonApiException(ErrorHelper::create('Failed to get filter template'));
            }
            $filterTemplate = $filterTemplate->template;
            $filterValues = [implode(',', $filterValues)];
            $filters = json_decode(vsprintf($filterTemplate, $filterValues), true);
        }

        try {
            $result = $this->integrationHelper->scheduleService(
                $integration->server,
                $integration->username,
                $serviceId,
                $filters
            );

            event(new ServiceScheduled($service));
        } catch (Exception $e) {
            event(new ServiceScheduleFailed($service, $e));

            throw new JsonApiException(ErrorHelper::create('Failed to schedule service', sprintf('Error: %s', $e->getMessage())));
        }

        return $this->find(sprintf('%s|%s', $integrationUsername, $result['run_id']));
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
    public function query(EncodingParametersInterface $parameters): ?Collection
    {
        $parameters = $parameters->toArray();
        $company = Auth::user()->company;
        $integration = $company->integrations->firstWhere('id', $parameters['integration_id']);

        if (!$company || !$integration) {
            return null;
        }

        unset($parameters['integration_id']);
        return $this->integrationHelper
            ->getFilteredServiceLog($integration['server'], $integration['username'], $parameters)
            ->map(function ($serviceLog) {
                return new ServiceLog($serviceLog);
            })->reverse();
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
     */
    public function find($resourceId): ?ServiceLog
    {
        $ids = explode('|', $resourceId);

        if (count($ids) !== 2) {
            throw new JsonApiException(ErrorHelper::create(
                'Failed to get service-log',
                'The id must be the integration username and the run id separated by pipe. E.g. custom_peaks|35.'
            ));
        }

        $integrationUsername = $ids[0];
        $runId = $ids[1];
        $integration = Integration::firstWhere(['username' => $integrationUsername]);

        if (!$integration) {
            throw new JsonApiException(ErrorHelper::create(
                'Failed to get integration',
                sprintf('Failed to get integration %s', $integrationUsername)
            ));
        }

        try {
            $result = $this->integrationHelper->getServiceLog($integration->server, $integration->username, ['query' => ['id' => $runId]]);
        } catch (Exception $e) {
            throw new JsonApiException(ErrorHelper::create('Failed to get service-log', sprintf('Error: %s', $e->getMessage())));
        }

        if ($result === null) {
            return null;
        }

        return new ServiceLog($result);
    }

    /**
     * @inheritDoc
     */
    public function findMany(array $resourceIds)
    {
        // TODO: Implement findMany() method.
    }
}
