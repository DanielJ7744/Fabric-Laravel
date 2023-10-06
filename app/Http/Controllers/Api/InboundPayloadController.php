<?php

namespace App\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreInboundPayloadRequest;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use Illuminate\Http\JsonResponse;

class InboundPayloadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInboundPayloadRequest  $request
     * @param Integration  $integration
     * @param InboundEndpoint  $endpoint
     * @return JsonResponse
     */
    public function store(StoreInboundPayloadRequest $request, Integration $integration, InboundEndpoint $endpoint): JsonResponse
    {
        try {
            /**
             * Temporary: The payload database cannot currently be accessed directly
             * so we must post the payload to Hasura for now.
             */
            Hasura::storePayload($endpoint, $request->all());

            return response()->json([
                'message' => 'Payload stored successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Payload could not be stored.',
            ], 500);
        }
    }
}
