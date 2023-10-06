<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertManager;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'alert-manager';

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
        return [];
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'company_id' => $resource->company_id,
            'service_id' => $resource->service_id,
            'config_id' => $resource->config_id,
            'recipient_id' => $resource->recipient_id,
            'alert_type' => $resource->alert_type,
            'send_from' => $resource->send_from,
            'dispatched-at' => $resource->dispatched_at,
            'failed-at' => $resource->failed_at,
            'seen_on_dashboard' => $resource->seen_on_dashboard,
            'service_log_run_ids' => $resource->service_log_run_ids
        ];
    }

    public function getRelationships($alertManager, $isPrimary, array $includeRelationships)
    {
        return [
            'company' => [
                self::DATA => function () use ($alertManager) {
                    return $alertManager->company;
                },
            ],
            'recipients' => [
                self::DATA => function () use ($alertManager) {
                    return $alertManager->recipients;
                },
            ],
            'configs' => [
                self::DATA => function () use ($alertManager) {
                    return $alertManager->configs;
                },
            ],
        ];
    }
}
