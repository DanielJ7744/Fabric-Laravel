<?php

namespace App\Http\Api\v1\ServiceLogs;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'service-logs';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($servicelog): array
    {
        return [
            'runId' => $servicelog->getRouteKey(),
            'serviceId' => $servicelog->getServiceId(),
            'fromFactory' => $servicelog->getFromFactory(),
            'fromEnvironment' => $servicelog->getFromEnvironment(),
            'toFactory' => $servicelog->getToFactory(),
            'toEnvironment' => $servicelog->getToEnvironment(),
            'username' => $servicelog->getUsername(),
            'requestedBy' => $servicelog->getRequestedBy(),
            'status' => $servicelog->getStatus(),
            'runtime' => $servicelog->getRuntime(),
            'errorCount' => $servicelog->getErrorCount(),
            'filters' => $servicelog->getFilters(),
            'dueAt' => $servicelog->getDueAt(),
            'startedAt' => $servicelog->getStartedAt(),
            'finishedAt' => $servicelog->getFinishedAt(),
        ];
    }
}
