<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterTypeResource;
use App\Models\Fabric\FilterType;
use App\Queries\FilterTypeQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterTypeQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, FilterTypeQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FilterType::class);

        $filterTypes = $query->paginate($request->perPage);

        return FilterTypeResource::collection($filterTypes);
    }

    /**
     * Display the specified resource.
     *
     * @param FilterType $filterType
     * @param FilterTypeQuery $query
     *
     * @return FilterTypeResource
     *
     * @throws AuthorizationException
     */
    public function show(FilterType $filterType, FilterTypeQuery $query): FilterTypeResource
    {
        $this->authorize('view', $filterType);

        $filterType = $query
            ->whereKey($filterType)
            ->firstOrFail();

        return new FilterTypeResource($filterType);
    }
}
