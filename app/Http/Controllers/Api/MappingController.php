<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Fabric\Mapping;
use App\Models\Tapestry\Service;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\Integration;
use App\Http\Controllers\Controller;
use App\Http\Resources\MappingResource;
use App\Http\Helpers\ElasticsearchHelper;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Requests\Api\StoreMappingRequest;
use Illuminate\Auth\Access\AuthorizationException;

class MappingController extends Controller
{
    private ElasticsearchHelper $elasticsearchHelper;

    /**
     * Create a new controller instance.
     *
     * @param ElasticsearchHelper $elasticsearchHelper
     */
    public function __construct(ElasticsearchHelper $elasticsearchHelper)
    {
        $this->elasticsearchHelper = $elasticsearchHelper;
    }

    /**
     * Retrieve the specified mapping file
     *
     * @param string $mappingName
     *
     * @return MappingResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function show(string $mappingName): MappingResource
    {
        $this->authorize('view', Mapping::class);

        $result = $this->elasticsearchHelper->get(mb_strtolower(sprintf('mappings/storage/%s', $mappingName)));
        if (!isset($result['_source'])) {
            abort(500, 'Unable to retrieve mappings as no content was given.');
        }
        $source = $result['_source'];

        return new MappingResource((object) $source);
    }

    /**
     * Create a new mapping file
     *
     * @param StoreMappingRequest $request
     *
     * @return MappingResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function store(StoreMappingRequest $request): MappingResource
    {
        $this->authorize('create', Mapping::class);

        $attributes = $request->validated();
        $username = isset($attributes['username_override'])
            ? $attributes['username_override']
            : Integration::findOrFail($attributes['integration_id'])->username;
        $createdAt = Carbon::now()->toRfc3339String();
        $searchField = $attributes['searchField'] ?? sprintf('%s_%s', $username, $attributes['mapping_name']);
        $id = sprintf('%s_%s', $username, $attributes['mapping_name']);
        $fullMappingName = sprintf('mappings/storage/%s', $id);
        $fullArchiveMappingName = sprintf('mappings-archive/storage/%s-%s', $id, $createdAt);
        $data = [
            'username' => $username,
            'search_field' => $searchField,
            'mapping_name' => $attributes['mapping_name'],
            'created_at' => $createdAt,
            'content' => $attributes['content'],
        ];

        try {
            // try to get mapping first
            $source = $this->elasticsearchHelper->get($fullMappingName)['_source'];
            // if get succeeds, archive the old file
            $this->elasticsearchHelper->post($fullArchiveMappingName, $source);
            // update the new one
            $this->elasticsearchHelper->put($fullMappingName, $data);
        } catch (Exception $exception) {
            // if get 404's then create the file
            if ($exception instanceof ClientException && $exception->getResponse()->getStatusCode() === 404) {
                $this->elasticsearchHelper->post($fullMappingName, $data);
            } else {
                abort(500, sprintf("Failed to create mapping %s\n%s", $fullMappingName, $exception->getMessage()));
            }
        }

        $returnAttributes = [
            'username' => $username,
            'search_field' => $searchField,
            'mapping_name' => $attributes['mapping_name'],
            'overrides' => null,
            'created_at' => $createdAt,
            'content' => $attributes['content']
        ];

        return new MappingResource((object) $returnAttributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $mappingName
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function destroy(string $mappingName): JsonResponse
    {
        $this->authorize('delete', Mapping::class);

        $fullMappingName = sprintf('mappings/storage/%s', $mappingName);

        $this->elasticsearchHelper->delete($fullMappingName);

        return response()->json([
            'message' => 'Mapping deleted successfully.'
        ]);
    }
}
