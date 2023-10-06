<?php

namespace App\Http\Api\v1\ReportSyncResults;

use App\Http\Helpers\ReportSyncHelper;
use App\Models\Fabric\ReportSyncResult;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Exception;

class Adapter extends AbstractResourceAdapter
{
    private ReportSyncHelper $reportSyncHelper;

    public function __construct(ReportSyncHelper $reportSyncHelper)
    {
        $this->reportSyncHelper = $reportSyncHelper;
    }

    /**
     * @inheritDoc
     */
    protected function createRecord(ResourceObject $resource)
    {
        // TODO: Implement createRecord() method.
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
        $parameters = $parameters->getUnrecognizedParameters() ?: [];
        $companyId = Auth::user()->company->id;
        $pageNumber = array_key_exists('page_number', $parameters) ? (int)$parameters['page_number'] : 1;
        $filters = $this->reportSyncHelper->getFiltersFromQueryString($parameters, $pageNumber);
        $integrationIds = array_key_exists('integrations', $parameters) ? explode(',', $parameters['integrations']) : null;
        $integrations = $this->reportSyncHelper->getIntegrationsByCompanyOrIds($companyId, $integrationIds);
        $entityCountsPerIntegration = $this->reportSyncHelper->getEntityCountsForIntegrations($filters, $integrations);
        try {
            $resultsAllIntegrations = $this->reportSyncHelper->getEntitiesFromApi($pageNumber, $integrations, $filters);
            $resultsAllIntegrations['total_results'] = $this->reportSyncHelper->paginationTotalResults($entityCountsPerIntegration);
            $resultsAllIntegrations['counts_per_integration'] = $entityCountsPerIntegration;
            $entityCountsPerIntegration = $entityCountsPerIntegration->toArray();
            $resultsAllIntegrations['pages_per_integration'] = $this->getPagesPerIntegration($entityCountsPerIntegration, $pageNumber);
            $resultsAllIntegrations['results_offset'] = $this->reportSyncHelper->paginationOffset($pageNumber);
        } catch (Exception $e) {
            $errorMessage = 'Failed to get entities from Tapestry';
            Log::warning($errorMessage);
            $error = new Error(null, null, 500, 500, 'Failed to get filter options', $e->getMessage());
            throw new JsonApiException($error);
        }

        return new ReportSyncResult($resultsAllIntegrations);
    }

    /**
     * Get the number of pages per integration
     *
     * @param array $entityCountsPerIntegration
     * @param int $pageNumber
     *
     * @return array
     */
    protected function getPagesPerIntegration(array $entityCountsPerIntegration, int $pageNumber): array
    {
        $pagesPerIntegration = [];
        foreach ($entityCountsPerIntegration as $entityCount) {
            $totalPages = $this->reportSyncHelper->paginationPageCount($entityCount['entity_count_total']);
            $pagesPerIntegration[] = [
                'integration_id' => $entityCount['integration_id'],
                'total_pages' => $totalPages,
                'current_page' => $pageNumber,
                'next_page' => $this->reportSyncHelper->paginationNextPage($pageNumber, $totalPages),
                'previous_page' => $this->reportSyncHelper->paginationPreviousPage($pageNumber),
                'page_size' => $this->reportSyncHelper->paginationLimit()
            ];
        }

        return $pagesPerIntegration;
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
    public function find($resourceId)
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function findMany(array $resourceIds)
    {
        // TODO: Implement findMany() method.
    }

}
