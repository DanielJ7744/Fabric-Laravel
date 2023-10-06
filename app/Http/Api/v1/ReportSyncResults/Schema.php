<?php

namespace App\Http\Api\v1\ReportSyncResults;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'report-sync-results';

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
    public function getAttributes($reportSyncResults)
    {
        return [
            'integrations_no_entities' => $reportSyncResults->getIntegrationsNoEntities(),
            'entities' => $reportSyncResults->getEntities(),
            'results_offset' => $reportSyncResults->getResultsOffset(),
            'total_results' => $reportSyncResults->getTotalResults(),
            'counts_per_integration' => $reportSyncResults->getCountsPerIntegration(),
            'pages_per_integration' => $reportSyncResults->getPagesPerIntegration(),
            'available_entities' => $reportSyncResults->getAvailableEntities(),
        ];
    }
}
