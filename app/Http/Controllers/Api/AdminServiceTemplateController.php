<?php

namespace App\Http\Controllers\Api;

use App\Queries\ServiceTemplateQuery;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\ServiceTemplate;
use App\Http\Resources\ServiceTemplateResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreServiceTemplateRequest;
use App\Http\Requests\Api\AdminUpdateServiceTemplateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminServiceTemplateController extends Controller
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
    public function index(ServiceTemplateQuery $serviceTemplateQuery): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ServiceTemplate::class);

        $serviceTemplates = $serviceTemplateQuery->get();

        return ServiceTemplateResource::collection($serviceTemplates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreServiceTemplateRequest $request
     *
     * @return ServiceTemplateResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreServiceTemplateRequest $request): ServiceTemplateResource
    {
        $this->authorize('create', [ServiceTemplate::class, $request->validated()]);

        $serviceTemplate = ServiceTemplate::create($request->validated());

        return new ServiceTemplateResource($serviceTemplate);
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
    public function show(ServiceTemplate $serviceTemplate, ServiceTemplateQuery $serviceTemplateQuery): ServiceTemplateResource
    {
        $this->authorize('view', $serviceTemplate);

        $serviceTemplate = $serviceTemplateQuery->whereKey($serviceTemplate)->firstOrFail();

        return new ServiceTemplateResource($serviceTemplate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateServiceTemplateRequest $request
     * @param ServiceTemplate $serviceTemplate
     *
     * @return ServiceTemplateResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateServiceTemplateRequest $request, ServiceTemplate $serviceTemplate): ServiceTemplateResource
    {
        $this->authorize('update', $serviceTemplate);

        $serviceTemplate->update($request->validated());

        return new ServiceTemplateResource($serviceTemplate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServiceTemplate $serviceTemplate
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(ServiceTemplate $serviceTemplate): JsonResponse
    {
        $this->authorize('delete', $serviceTemplate);

        $serviceTemplate->delete();

        return response()->json([
            'message' => 'Service template deleted successfully.'
        ]);
    }
}
