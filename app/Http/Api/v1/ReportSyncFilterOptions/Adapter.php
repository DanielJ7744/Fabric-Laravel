<?php

namespace App\Http\Api\v1\ReportSyncFilterOptions;

use App\Http\Helpers\ReportSyncHelper;
use App\Models\Fabric\Entity;
use App\Models\Fabric\ReportSyncFilterOption;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;

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
        $companyId = Auth::user()->company->id;
        $errors = new Collection();
        $integrations = $this->reportSyncHelper->getIntegrationsByCompanyOrIds($companyId, null);
        $statuses = collect(['Ok', 'Sent', 'Failed', 'Pending']);
        $types = Entity::all();

        $systemChains = $integrations->flatMap(function ($integration) use ($errors) {
            try {
                $response = $this->reportSyncHelper->getSystemChainsFromApi($integration);
            } catch (Exception $e) {
                $error = 'Failed to get filter options';
                Log::warning($error);
                $errors->push($error);
                return [];
            }

            if ($response->isNotEmpty()) {
                return $response->map(function ($chainData) use ($integration) {
                    $systemChain = [];
                    $systemChain['name'] = $chainData['system_chain'];
                    $systemChain['integration_id'] = $integration['id'];
                    return $systemChain;
                });
            }

            return false;
        })->reject(fn($data) => $data === false); //remove failed results

        if ($errors->count() > 0) {
            $errorMessage = $errors->implode("\n");
            $error = new Error(null, null, 500, 500, 'Failed to get filter options', $errorMessage);
            throw new JsonApiException($error);
        }

        return new ReportSyncFilterOption([
           'integrations' => $integrations,
           'system_chains' => $systemChains,
           'statuses' => $statuses,
           'types' => $types,
        ]);
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
