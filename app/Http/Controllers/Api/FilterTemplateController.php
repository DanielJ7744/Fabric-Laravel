<?php

namespace App\Http\Controllers\Api;

use App\Queries\FilterTemplateQuery;
use App\Http\Controllers\Controller;
use App\Models\Fabric\FilterTemplate;
use App\Http\Resources\FilterTemplateResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FilterTemplateQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(FilterTemplateQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FilterTemplate::class);

        $filterTemplates = $query->paginate();

        return FilterTemplateResource::collection($filterTemplates);
    }

    /**
     * Display the specified resource.
     *
     * @param FilterTemplate $filterTemplate
     * @param FilterTemplateQuery $query
     *
     * @return FilterTemplateResource
     *
     * @throws AuthorizationException
     */
    public function show(FilterTemplate $filterTemplate, FilterTemplateQuery $query): FilterTemplateResource
    {
        $this->authorize('view', $filterTemplate);

        $filterTemplate = $query->whereKey($filterTemplate)->firstOrFail();

        return new FilterTemplateResource($filterTemplate);
    }
}
