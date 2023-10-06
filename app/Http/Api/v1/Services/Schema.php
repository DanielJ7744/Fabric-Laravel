<?php

namespace App\Http\Api\v1\Services;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'services';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    public function getIncludePaths(): array
    {
        return [
            'integration',
            'fromEntity',
            'fromSystem',
            'fromFilterTemplate',
            'toEntity',
            'toSystem',
        ];
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($service)
    {
        return [
            'serviceId' => $service->getServiceId(),
            'status' => $service->getStatus(),
            'description' => $service->getDescription(),
            'fromFactory' => $service->getFromFactory(),
            'fromEnvironment' => $service->getFromEnvironment(),
            'fromOptions' => $service->getFromOptions(),
            'fromMapping' => $service->getFromMapping(),
            'toFactory' => $service->getToFactory(),
            'toEnvironment' => $service->getToEnvironment(),
            'toOptions' => $service->getToOptions(),
            'toMapping' => $service->getToMapping(),
            'schedule' => $service->getSchedule(),
            'filters' => $service->getFilters(),
        ];
    }

    public function getRelationships($service, $isPrimary, array $includeRelationships)
    {
        return [
            'integration' => [
                self::DATA => function () use ($service) {
                    return $service->getIntegration();
                },
            ],
            'fromEntity' => [
                self::DATA => function () use ($service) {
                    return $service->getFromEntity();
                },
            ],
            'fromSystem' => [
                self::DATA => function () use ($service) {
                    return $service->getFromSystem();
                },
            ],
            'fromFilterTemplate' => [
                self::DATA => function () use ($service) {
                    return $service->getFromFilterTemplate();
                },
            ],
            'toEntity' => [
                self::DATA => function () use ($service) {
                    return $service->getToEntity();
                },
            ],
            'toSystem' => [
                self::DATA => function () use ($service) {
                    return $service->getToSystem();
                },
            ],
        ];
    }
}
