<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreServiceTemplateRequest;
use App\Http\Resources\ServiceTemplateResource;
use App\Models\Fabric\ServiceTemplate;
use App\Queries\ServiceTemplateQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceTemplateController extends Controller
{
    /**
     * Get all service templates
     *
     * @param ServiceTemplateQuery $serviceTemplateQuery
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(ServiceTemplateQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ServiceTemplate::class);

        $serviceTemplates = $query->get();

        return ServiceTemplateResource::collection($serviceTemplates);
    }

    /**
     * Show a service template
     *
     * @param ServiceTemplate $serviceTemplate
     * @param ServiceTemplateQuery $serviceTemplateQuery
     *
     * @return ServiceTemplateResource
     *
     * @throws AuthorizationException
     */
    public function show(ServiceTemplate $serviceTemplate, ServiceTemplateQuery $query): ServiceTemplateResource
    {
        $this->authorize('view', $serviceTemplate);

        $serviceTemplate = $query->whereKey($serviceTemplate)->firstOrFail();

        return new ServiceTemplateResource($serviceTemplate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreServiceTemplateRequest $request
     *
     * @return ServiceTemplateResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreServiceTemplateRequest $request): ServiceTemplateResource
    {
        $this->authorize('create', [ServiceTemplate::class, $request->validated()]);

        $serviceTemplate = ServiceTemplate::create($request->validated());

        return new ServiceTemplateResource($serviceTemplate);
    }
}
