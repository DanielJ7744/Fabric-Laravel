<?php

namespace App\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreInboundEndpointRequest;
use App\Http\Requests\Api\UpdateInboundEndpointRequest;
use App\Http\Resources\InboundEndpointResource;
use App\Models\Fabric\Company;
use App\Models\Fabric\InboundEndpoint;
use App\Queries\InboundEndpointQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InboundEndpointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param InboundEndpointQuery  $query
     * @return AnonymousResourceCollection
     */
    public function index(InboundEndpointQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', InboundEndpoint::class);

        $inboundEndpoints = $query->get();

        return InboundEndpointResource::collection($inboundEndpoints);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInboundEndpointRequest  $request
     * @return InboundEndpointResource
     */
    public function store(StoreInboundEndpointRequest $request): InboundEndpointResource
    {
        $this->authorize('create', InboundEndpoint::class);

        /**
         * Temporary: We must create a duplicate Hasura endpoint record and store the id
         * until we are able to untangle this process.
         */
        $hasuraEndpoint = Hasura::createEndpoint(Company::current(), $request->slug, Company::current()->name);

        $endpoint = InboundEndpoint::create($request->validated() + [
            'external_endpoint_id' => $hasuraEndpoint->id,
        ]);

        return new InboundEndpointResource($endpoint);
    }

    /**
     * Display the specified resource.
     *
     * @param InboundEndpoint  $inboundEndpoint
     * @param InboundEndpointQuery  $query
     * @return InboundEndpointResource
     */
    public function show(InboundEndpoint $inboundEndpoint, InboundEndpointQuery  $query): InboundEndpointResource
    {
        $this->authorize('view', $inboundEndpoint);

        $inboundEndpoint = $query->whereKey($inboundEndpoint)->firstOrFail();

        return new InboundEndpointResource($inboundEndpoint);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInboundEndpointRequest  $request
     * @param InboundEndpoint  $inboundEndpoint
     * @return InboundEndpointResource
     */
    public function update(UpdateInboundEndpointRequest $request, InboundEndpoint $inboundEndpoint): InboundEndpointResource
    {
        $this->authorize('update', $inboundEndpoint);

        $inboundEndpoint->update($request->validated());

        return new InboundEndpointResource($inboundEndpoint);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param InboundEndpoint  $inboundEndpoint
     * @return JsonResponse
     */
    public function destroy(InboundEndpoint $inboundEndpoint): JsonResponse
    {
        $this->authorize('delete', $inboundEndpoint);

        $inboundEndpoint->delete();

        return response()->json([
            'message' => 'Endpoint deleted successfully.'
        ]);
    }
}
