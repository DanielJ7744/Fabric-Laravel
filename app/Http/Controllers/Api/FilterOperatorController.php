<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\FilterOperatorQuery;
use App\Models\Fabric\FilterOperator;
use App\Http\Resources\FilterOperatorResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterOperatorQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, FilterOperatorQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FilterOperator::class);

        $filterFields = $query->paginate($request->perPage);

        return FilterOperatorResource::collection($filterFields);
    }

    /**
     * Display the specified resource.
     *
     * @param FilterOperator $filterOperator
     * @param FilterOperatorQuery $query
     *
     * @return FilterOperatorResource
     *
     * @throws AuthorizationException
     */
    public function show(FilterOperator $filterOperator, FilterOperatorQuery $query): FilterOperatorResource
    {
        $this->authorize('view', $filterOperator);

        $filterOperator = $query
            ->whereKey($filterOperator)
            ->firstOrFail();

        return new FilterOperatorResource($filterOperator);
    }
}
