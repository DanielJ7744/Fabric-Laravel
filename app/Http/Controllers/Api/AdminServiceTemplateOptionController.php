<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\ServiceTemplateOptionResource;
use App\Http\Requests\Api\AdminStoreServiceTemplateOptionRequest;
use App\Http\Requests\Api\AdminUpdateServiceTemplateOptionRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminServiceTemplateOptionController extends Controller
{
    /**
     * Get all service templates
     *
     * @param ServiceTemplate $serviceTemplate
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(ServiceTemplate $serviceTemplate): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ServiceTemplateOption::class);

        $serviceTemplateOptions = $serviceTemplate
            ->serviceTemplateOptions()
            ->with('serviceOption')
            ->get();

        return ServiceTemplateOptionResource::collection($serviceTemplateOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreServiceTemplateOptionRequest $request
     * @param ServiceTemplate $serviceTemplate
     *
     * @return ServiceTemplateOptionResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreServiceTemplateOptionRequest $request, ServiceTemplate $serviceTemplate): ServiceTemplateOptionResource
    {
        $this->authorize('create', ServiceTemplateOption::class);

        $serviceTemplateOption = $serviceTemplate
            ->serviceTemplateOptions()
            ->create($request->validated());

        return new ServiceTemplateOptionResource($serviceTemplateOption);
    }

    /**
     * Show a service template
     *
     * @param ServiceTemplate $serviceTemplate
     * @param ServiceTemplateOption $option
     *
     * @return ServiceTemplateOptionResource
     *
     * @throws AuthorizationException
     */
    public function show(ServiceTemplate $serviceTemplate, ServiceTemplateOption $option): ServiceTemplateOptionResource
    {
        $this->authorize('view', $option);

        $serviceTemplateOption = $serviceTemplate
            ->serviceTemplateOptions()
            ->with('serviceOption')
            ->whereKey($option)
            ->firstOrFail();

        return new ServiceTemplateOptionResource($serviceTemplateOption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateServiceTemplateOptionRequest $request
     * @param ServiceTemplate $serviceTemplate
     * @param ServiceTemplateOption $option
     *
     * @return ServiceTemplateOptionResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateServiceTemplateOptionRequest $request, ServiceTemplate $serviceTemplate, ServiceTemplateOption $option): ServiceTemplateOptionResource
    {
        $this->authorize('update', $option);

        $option->update($request->validated());

        return new ServiceTemplateOptionResource($option);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServiceTemplate $serviceTemplate
     * @param ServiceTemplateOption $option
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(ServiceTemplate $serviceTemplate, ServiceTemplateOption $option): JsonResponse
    {
        $this->authorize('delete', $option);

        $option->delete();

        return response()->json([
            'message' => 'Service template option deleted successfully.'
        ]);
    }
}
