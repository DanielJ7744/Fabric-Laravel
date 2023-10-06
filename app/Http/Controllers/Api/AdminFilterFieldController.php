<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminStoreFilterFieldRequest;
use App\Http\Requests\Api\AdminUpdateFilterFieldRequest;
use App\Http\Resources\FilterFieldResource;
use App\Models\Fabric\FilterField;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class AdminFilterFieldController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFilterFieldRequest $request
     *
     * @return FilterFieldResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFilterFieldRequest $request): FilterFieldResource
    {
        $this->authorize('create', FilterField::class);

        $filterField = FilterField::create($request->validated());

        return new FilterFieldResource($filterField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFilterFieldRequest $request
     * @param FilterField $filterField
     *
     * @return FilterFieldResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFilterFieldRequest $request, FilterField $filterField): FilterFieldResource
    {
        $this->authorize('update', $filterField);

        $filterField->update($request->validated());

        return new FilterFieldResource($filterField);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FilterField $filterField
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FilterField $filterField): JsonResponse
    {
        $this->authorize('delete', $filterField);

        $filterField->delete();

        return response()->json([
            'message' => 'Filter field deleted successfully.'
        ]);
    }
}
