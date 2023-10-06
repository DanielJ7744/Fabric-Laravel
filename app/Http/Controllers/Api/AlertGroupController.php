<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAlertGroupRequest;
use App\Http\Requests\Api\UpdateAlertGroupRequest;
use App\Http\Resources\AlertGroupResource;
use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Company;
use App\Queries\AlertGroupQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlertGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Queries\AlertGroupQuery  $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(AlertGroupQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', AlertGroups::class);

        $alertGroups = $query->get();

        return AlertGroupResource::collection($alertGroups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAlertGroupRequest $request
     * @return AlertGroupResource
     * @throws AuthorizationException
     */
    public function store(StoreAlertGroupRequest $request): AlertGroupResource
    {
        $this->authorize('create', AlertGroups::class);

        $alertGroup = Company::current()
            ->alertGroups()
            ->create($request->validated());

        return new AlertGroupResource($alertGroup);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alerting\AlertGroups  $alertGroup
     * @param \App\Queries\AlertGroupQuery  $query
     * @return \App\Http\Resources\AlertGroupResource
     */
    public function show(AlertGroups $alertGroup, AlertGroupQuery $query): AlertGroupResource
    {
        $this->authorize('view', $alertGroup);

        $entity = $query
            ->whereKey($alertGroup)
            ->firstOrFail();

        return new AlertGroupResource($entity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAlertGroupRequest $request
     * @param AlertGroups $alertGroup
     * @return AlertGroupResource
     * @throws AuthorizationException
     */
    public function update(UpdateAlertGroupRequest $request, AlertGroups $alertGroup): AlertGroupResource
    {
        $this->authorize('update', $alertGroup);

        $alertGroup->update($request->validated());

        return new AlertGroupResource($alertGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AlertGroups  $alertGroup
     * @return JsonResponse
     */
    public function destroy(AlertGroups $alertGroup): JsonResponse
    {
        $this->authorize('delete', $alertGroup);

        $alertGroup->delete();

        return response()->json([
            'message' => 'Alert group successfully'
        ]);
    }
}
