<?php

namespace App\Http\Api\v1\ReportSyncFilterOptions;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'report-sync-filter-options';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($reportSyncFilterOptions)
    {
        return [
            'integrations' => $reportSyncFilterOptions->getIntegrations(),
            'system_chains' => $reportSyncFilterOptions->getSystemChains(),
            'statuses' => $reportSyncFilterOptions->getStatuses(),
            'types' => $reportSyncFilterOptions->getTypes(),
        ];
    }
}
