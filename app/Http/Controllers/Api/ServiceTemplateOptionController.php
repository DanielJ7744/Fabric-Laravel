<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreServiceTemplateRequest;
use App\Http\Resources\ServiceTemplateOptionResource;
use App\Http\Resources\ServiceTemplateResource;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use App\Queries\ServiceTemplateQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceTemplateOptionController extends Controller
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
}
