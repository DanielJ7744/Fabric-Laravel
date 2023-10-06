<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertConfigs;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'alert-configs';

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
            'service_id' => $resource->service_id,
            'throttle_value' => $resource->throttle_value,
            'error_alert_status' => $resource->error_alert_status,
            'error_alert_threshold' => $resource->error_alert_threshold,
            'warning_alert_status' => $resource->warning_alert_status,
            'warning_alert_threshold' => $resource->warning_alert_threshold,
            'frequency_alert_status' => $resource->frequency_alert_status,
            'frequency_alert_threshold' => $resource->frequency_alert_threshold,
            'alert_frequency' => $resource->alert_frequency,
            'alert_status' => $resource->alert_status,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
        ];
    }
}
