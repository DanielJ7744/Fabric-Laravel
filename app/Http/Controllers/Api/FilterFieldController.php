<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\FilterFieldQuery;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\FilterField;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilterFieldResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreFilterFieldRequest;
use App\Http\Requests\Api\AdminUpdateFilterFieldRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterFieldQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, FilterFieldQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FilterField::class);

        $filterFields = $query->paginate($request->perPage);

        return FilterFieldResource::collection($filterFields);
    }

    /**
     * Display the specified resource.
     *
     * @param FilterField $filterField
     * @param FilterFieldQuery $query
     *
     * @return FilterFieldResource
     *
     * @throws AuthorizationException
     */
    public function show(FilterField $filterField, FilterFieldQuery $query): FilterFieldResource
    {
        $this->authorize('view', $filterField);

        $filterField = $query
            ->whereKey($filterField)
            ->firstOrFail();

        return new FilterFieldResource($filterField);
    }
}
