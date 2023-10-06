<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateService;
use App\Enums\Systems;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ElasticsearchHelper;
use App\Http\Helpers\IntegrationHelper;
use App\Http\Helpers\ServiceFilterHelper;
use App\Http\Helpers\ServiceHelper;
use App\Http\Requests\Api\StoreServiceRequest;
use App\Http\Resources\Tapestry\ServiceResource;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Tapestry\Service;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class IntegrationServiceController extends Controller
{
    private ElasticsearchHelper $elasticsearchHelper;

    /**
     * Create a new controller instance.
     *
     * @param ServiceHelper $serviceHelper
     * @param IntegrationHelper $integrationHelper
     * @param ElasticsearchHelper $elasticsearchHelper
     */
    public function __construct(ServiceHelper $serviceHelper, IntegrationHelper $integrationHelper, ElasticsearchHelper $elasticsearchHelper)
    {
        $this->serviceHelper = $serviceHelper;
        $this->integrationHelper = $integrationHelper;
        $this->elasticsearchHelper = $elasticsearchHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Integration $integration
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Integration $integration): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Service::class);

        $services = $integration
            ->services()
            ->with('integration')
            ->paginate();

        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreServiceRequest $request
     * @param Integration $integration
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function store(StoreServiceRequest $request, Integration $integration): JsonResponse
    {
        $this->authorize('create', Service::class);

        $serviceTemplate = ServiceTemplate::with([
            'source.system',
            'source.entity',
            'source.schemas',
            'destination.system',
            'destination.entity',
            'destination.schemas'
        ])->find($request->service_template_id);

        $sourceSystem = $serviceTemplate->sourceSystem;
        $sourceFactory = $serviceTemplate->sourceFactory;
        $destinationSystem = $serviceTemplate->destinationSystem;
        $destinationFactory = $serviceTemplate->destinationFactory;

        $attributes = $request->validated();
        $attributes['service_template_id'] = $request->service_template_id;

        try {
            $mappingNames = Service::cloneDefaultMappings($integration, $serviceTemplate, $this->elasticsearchHelper);
            $attributes['from_mapping'] = $mappingNames['from_mapping'];
            $attributes['to_mapping'] = $mappingNames['to_mapping'];
        } catch (Throwable $throwable) {
            $attributes['from_mapping'] = null;
            $attributes['to_mapping'] = null;
        }

        $attributes['status'] = false;
        $attributes['from_factory'] = $this->serviceHelper::getBaseFactoryString(
            $sourceSystem->factory_name,
            'Pull',
            $sourceFactory->name
        );
        $attributes['to_factory'] = $this->serviceHelper::getBaseFactoryString(
            $destinationSystem->factory_name,
            'Push',
            $destinationFactory->name
        );

        $sourceOptions = $serviceTemplate->tapestrySourceServiceOptions();
        $destinationOptions = $serviceTemplate->tapestryDestinationServiceOptions();
        $attributes['from_options']['filters'] = ServiceFilterHelper::destructFilters(
            $serviceTemplate->source,
            $serviceTemplate->source->getDefaultFilters()
        );

        $attributes['from_options'] = array_merge($sourceOptions, $attributes['from_options']);
        $attributes['to_options'] = array_merge($destinationOptions, $attributes['to_options'] ?? []);

        if (in_array($sourceSystem->name, [Systems::SFTP, Systems::INBOUND_API])) {
            $attributes['from_options']['entity_type'] = preg_replace(
                '/\s+/',
                '_',
                strtolower($serviceTemplate->source->entity->name)
            );
            $schema = $serviceTemplate->source->schemas->where('integration_id', $integration->id)->first();
            if (!$schema) {
                $schema = $serviceTemplate->source->schemas->first();
                if (!$schema) {
                    abort(500, 'No schema attached to factory');
                }
            }
            $attributes['from_options']['file_format'] = $schema->original_type;
        }

        $originalAttributes = $attributes;

        $attributes['from_options'] = json_encode($attributes['from_options'], JSON_PRETTY_PRINT);
        $attributes['to_options'] = json_encode($attributes['to_options'], JSON_PRETTY_PRINT);

        try {
            $createService = new CreateService($this->serviceHelper, $this->integrationHelper);
            $createService->runSystemSpecificFunctions($serviceTemplate, $integration, $originalAttributes);
            $result = $this->integrationHelper->createService($integration->server, $integration->username, ['fields' => $attributes]);

            $service = Service::findOrFail($result['id']);

            event('eloquent.created: ' . Service::class, $service); // Manually fire model event as creation happened elsewhere

            return (new ServiceResource($service))->response()->setStatusCode(201);
        } catch (Throwable $th) {
            return response()->json(['message' => sprintf('Could not create service. Error: %s', $th->getMessage())], 500);
        }
    }
}
