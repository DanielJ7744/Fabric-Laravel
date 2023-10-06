<?php

namespace App\Http\Helpers;

use App\Models\Fabric\Integration;
use App\Models\Tapestry\Service;
use App\Models\Tapestry\SyncReport;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ReportSyncHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all integrations for company, unless specific ids specified
     * @param int $companyId
     * @param array|null $ids
     * @return Collection
     */
    public function getIntegrationsByCompanyOrIds(int $companyId, array $ids = null): Collection
    {
        return (is_null($ids)) ?
            Integration::where('company_id', $companyId)->get() :
            Integration::whereIn('id', $ids)->get();
    }

    public function getSystemChainsFromApi(Integration $integration): Collection
    {
        $url = sprintf('%s/%s', ApiHelper::buildTapestryApiPath($integration->server, 'system-chains'), $integration->username);
        $json = $this->client->get($url)->getBody()->getContents();
        $return = json_decode($json, true)['data'];
        return collect($return);
    }

    public function getEntityCountsForIntegrations(array $filters, Collection $integrations): Collection
    {
        return $integrations->map(function ($integration) use ($filters) {
            $resultsForIntegration = [
                'integration_id' => $integration['id'],
                'integration_username' => $integration['username'],
                'integration_name' => $integration['name']
            ];

            try {
                $entityCountsResponse =  $this->getEntityCountsFromApi($integration, $filters);
            } catch (Exception $e) {
                Log::warning(sprintf(
                    'Tapestry did not return Entity Counts for integration %s (id: %d) -  %s',
                    $integration['name'],
                    $integration['id'],
                    $e->getMessage()
                ));

                $resultsForIntegration['got_results'] = false;
                $resultsForIntegration['message'] = 'Failed to retrieve entity counts';
                $resultsForIntegration['no_results_response'] = $e->getMessage();

                return $resultsForIntegration;
            }

            $resultsForIntegration['entity_counts'] = $entityCountsResponse;
            $resultsForIntegration['entity_count_total'] = $resultsForIntegration['entity_counts']
                ->sum('count');
            return $resultsForIntegration;
        });
    }

    public function getFiltersFromQueryString(array $params, int $pageNumber = null): array
    {
        $filters = [
            'first_service_id' => array_key_exists('first_service_id', $params) ? $params['first_service_id'] : null,
            'status' => array_key_exists('status', $params) ? $this->transformFilterValues('status', $params['status']) : null,
            'type' => array_key_exists('type', $params) ? $this->transformFilterValues('type', $params['type']) : null,
            'system_chain' => (array_key_exists('system_chain', $params)) ? $this->transformFilterValues('system_chain', $params['system_chain']) : null,
            'created_at' => array_key_exists('days', $params) ? $this->transformFilterValues('created_at', $params['days']) : null,
            'updated_at' => array_key_exists('updated_at_days', $params) ? $this->transformFilterValues('updated_at', $params['updated_at_days']) : null,
            'first_run_id' => array_key_exists('first_run_id', $params) ? $this->transformFilterValues('first_run_id', $params['first_run_id']) : null,
            'last_run_id' => array_key_exists('serviceRunIds', $params) ? $this->transformFilterValues('last_run_id', $params['serviceRunIds']) : null,
            'live_only' => array_key_exists('live_only', $params) ? $params['live_only'] : null,
            'updated_at_start' => array_key_exists('updated_at_start', $params) ? $params['updated_at_start'] : null,
            'updated_at_end' => array_key_exists('updated_at_end', $params) ? $params['updated_at_end'] : null,
            'sort_field' => array_key_exists('sort_field', $params) ? $params['sort_field'] : null,
            'sort_direction' => array_key_exists('sort_direction', $params) ? $params['sort_direction'] : null
        ];

        if (!is_null($pageNumber)) {
            $filters = array_merge($filters, [
                'offset' => $this->paginationOffset($pageNumber),
                'limit' => $this->paginationLimit()
            ]);
        }

        return $filters;
    }

    public function getEntityCountsFromApi(Integration $integration, array $filters): Collection
    {
        $url = sprintf(
            '%s/%s/filter/count?%s',
            ApiHelper::buildTapestryApiPath($integration['server'], 'entities'),
            $integration['username'],
            ApiHelper::buildTapestryQuery($filters)
        );

        return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['data']);
    }

    /*
     * Get a specific page of results from the API
     */
    public function getEntitiesFromApi(int $page, Collection $integrations, array $filters): array
    {
        $availableEntities = [];
        $offset = $this->paginationOffset($page);
        $limit = $this->paginationLimit();

        $filtersForCall = $filters;
        $filtersForCall['offset'] = $offset;
        $filtersForCall['limit'] = $limit;

        foreach ($integrations as $integration) {
            $syncReports = new SyncReport();
            $syncReports->setTable('idx_' . $integration->username);
            $availableEntities = array_unique(array_merge($availableEntities, $syncReports->getAvailableEntities()));
        }

        $integrationsData =  $integrations->map(function ($integration) use ($filtersForCall) {
            $filters = $filtersForCall;

            //create the nested data for the integration
            $integrationResponse = [
                'integration_id' => $integration['id'],
                'integration_username' => $integration['username'],
                'integration_name' => $integration['name'],
            ];

            // request the data from the API
            try {
                $url = sprintf('%s/%s/filter?%s', ApiHelper::buildTapestryApiPath($integration['server'], 'entities'), $integration['username'], ApiHelper::buildTapestryQuery($filters));
                $response = collect(json_decode($this->client->get($url)->getBody()->getContents(), true));
            } catch (Exception $e) {
                $integrationResponse['got_results'] = false;
                $integrationResponse['message'] = sprintf('Error getting results: %s', $e->getMessage());
                return $integrationResponse;
            }

            $integrationResponse['got_results'] = true;

            //loop through the returned data and format / add to $integrationResponse
            $keysToReturn = [
                'id',
                'common_ref',
                'source_id',
                'system_chain',
                'first_service_id',
                'first_run_id',
                'last_run_id',
                'type',
                'status',
                'message',
                'error',
                'created_at',
                'updated_at'
            ];
            $integrationResponse['entities'] = collect($response['data'])
                ->map(function ($entity) use ($integration, $keysToReturn, $filters) {
                    $requiredEntityData = collect($entity)->only($keysToReturn);
                    $requiredEntityData['error'] = $filters['status'] === 'failed' ? $entity['message'] : $entity['status'];

                    if (strtolower($entity['status']) === 'failed' && !empty($entity['first_service_id'])) {
                        $entityData = $this->getEntityResyncData($integration, $entity);
                        $requiredEntityData['resync_column'] = $entityData['resync_column'];
                        $requiredEntityData['filter_values'] = $entityData['filter_values'];
                        $requiredEntityData['filter_template_id'] = $entityData['filter_template_id'];
                    } else {
                        $requiredEntityData['resync_column'] = null;
                        $requiredEntityData['filter_values'] = null;
                        $requiredEntityData['filter_template_id'] = null;
                    }

                    return $requiredEntityData;
                });

            return $integrationResponse;
        });

        //transform the data to be have the entities at the top level
        $integrationsNoResults = $integrationsData->whereStrict('got_results', false);
        $integrationsWithResults = $integrationsData->whereStrict('got_results', true);

        $entitiesWithIntegrationNested = $integrationsWithResults->flatMap(function ($integration) {
            $entities = $integration['entities'];
            return $entities->transform(function ($entity) use ($integration) {
                $entity['integration'] = [
                    'id' => $integration['integration_id'],
                    'name' => $integration['integration_name'],
                    'username' => $integration['integration_username']
                ];
                return $entity;
            });
        });

        return [
            'integrations_no_entities' => $integrationsNoResults,
            'entities' => $entitiesWithIntegrationNested,
            'available_entities' => $availableEntities
        ];
    }

    public function getEntityResyncData(Integration $integration, array $idxRow, ElasticsearchHelper $elasticsearchHelper): ?array
    {
        $service = Service::find($idxRow['first_service_id']);
        if (!$service) {
            return null;
        }

        try {
            $sourceEntity = $service->getSourceEntity();
            $sourceSystem = $service->getSourceSystem();
            $sourceFactorySystem = $service->getSourceFactorySystem();
            $filterTemplate = ServiceHelper::getFilterTemplateByFactorySystem($sourceFactorySystem);

            if (!empty($filterTemplate)) {
                if (!empty($service->from_mapping)) {
                    $resourceId = mb_strtolower(sprintf('%s_%s', $integration['username'], $service->from_mapping));
                    $result = Cache::remember(
                        sprintf('%s_map', $resourceId),
                        now()->addMinutes(2),
                        fn () => $elasticsearchHelper->get(sprintf('mappings/storage/%s', $resourceId))
                    );
                    $mappingFields = json_decode($result['_source']['content'], true)['data_map'][strtolower(
                        $sourceEntity['name'] // TODO: Entity -> factory_name
                    )][0];
                } else {
                    $defaultMappingUrl = sprintf('%s/getDefaultContent/%s/%s/%s', ApiHelper::buildTapestryApiPath($integration['server'], 'mappings'), $sourceSystem->name, 'pull', strtolower($sourceEntity['name']) . '.json'); // TODO: Entity -> factory_name
                    $defaultMappingResponse = collect(json_decode($this->client->get($defaultMappingUrl)->getBody()->getContents(), true));

                    $mappingFields = json_decode($defaultMappingResponse['data'], true)['data_map'][strtolower(
                        $sourceEntity['name'] // TODO: Entity -> factory_name
                    )][0];
                }

                foreach ($mappingFields as $mappingDataItem) {
                    if (
                        !empty($mappingDataItem['pw_value']) &&
                        $mappingDataItem['pw_value'] === $filterTemplate->pw_value_field &&
                        !empty($mappingDataItem['pw_track']) &&
                        strtolower($mappingDataItem['pw_track']['type']) === strtolower($idxRow['type'])
                    ) {
                        return [
                            'resync_column' => $mappingDataItem['pw_track']['column'],
                            'filter_values' => [$idxRow[$mappingDataItem['pw_track']['column']]] ?? null,
                            'filter_template_id' => $filterTemplate->id
                        ];
                    }
                }
            }

            return null;
        } catch (Throwable $e) {
            return null;
        }
    }

    private function transformFilterValues(string $key, $value)
    {
        if (is_null($value)) {
            return null;
        }

        switch ($key) {
            case 'system_chain':
            case 'last_run_id':
                $value = str_replace(',', '|', $value);
                break;
            case 'created_at':
                $value = (ctype_digit($value) && $value <= 30) ? $value : null;
                break;
            default:
                $value = ctype_alnum(str_replace("_", "", $value)) ? $value : null;
                break;
        }

        return $value;
    }

    public function paginationTotalResults(Collection $entityCountsPerIntegration): int
    {
        return $entityCountsPerIntegration->sum('entity_count_total');
    }

    public function paginationPageCount(int $totalResults): int
    {
        return (int) ceil($totalResults / $this->paginationLimit());
    }

    public function paginationPreviousPage(int $currentPage): ?int
    {
        if (($currentPage - 1) < 1) {
            return null;
        }
        return $currentPage - 1;
    }

    public function paginationNextPage(int $currentPage, int $totalPages): ?int
    {
        $nextPageNum = $currentPage + 1;
        if ($nextPageNum > $totalPages) {
            return null;
        }
        return $nextPageNum;
    }

    public function paginationOffset(int $currentPage): int
    {
        return $this->paginationPreviousPage($currentPage) * $this->paginationLimit();
    }

    public function paginationLimit(): int
    {
        return 50;
    }
}
