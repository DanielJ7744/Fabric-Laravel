<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\FilterOperator;
use App\Http\Resources\FilterOperatorResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreFilterOperatorRequest;
use App\Http\Requests\Api\AdminUpdateFilterOperatorRequest;

class AdminFilterOperatorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFilterOperatorRequest $request
     *
     * @return FilterOperatorResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFilterOperatorRequest $request): FilterOperatorResource
    {
        $this->authorize('create', FilterOperator::class);

        $filterOperator = FilterOperator::create($request->validated());

        return new FilterOperatorResource($filterOperator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFilterOperatorRequest $request
     * @param FilterOperator $filterOperator
     *
     * @return FilterOperatorResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFilterOperatorRequest $request, FilterOperator $filterOperator): FilterOperatorResource
    {
        $this->authorize('update', $filterOperator);

        $filterOperator->update($request->validated());

        return new FilterOperatorResource($filterOperator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FilterOperator $filterOperator
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FilterOperator $filterOperator): JsonResponse
    {
        $this->authorize('delete', $filterOperator);

        $filterOperator->delete();

        return response()->json([
            'message' => 'Filter operator deleted successfully.'
        ]);
    }
}
