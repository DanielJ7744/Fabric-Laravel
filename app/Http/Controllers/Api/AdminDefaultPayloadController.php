<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\DefaultPayload;
use App\Http\Resources\DefaultPayloadResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreDefaultPayloadRequest;
use App\Http\Requests\Api\AdminUpdateDefaultPayloadRequest;

class AdminDefaultPayloadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreDefaultPayloadRequest $request
     *
     * @return DefaultPayloadResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreDefaultPayloadRequest $request): DefaultPayloadResource
    {
        $this->authorize('create', DefaultPayload::class);

        $defaultPayload = DefaultPayload::create($request->validated());

        return new DefaultPayloadResource($defaultPayload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateDefaultPayloadRequest $request
     * @param DefaultPayload $defaultPayload
     *
     * @return DefaultPayloadResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateDefaultPayloadRequest $request, DefaultPayload $defaultPayload): DefaultPayloadResource
    {
        $this->authorize('update', $defaultPayload);

        $defaultPayload->update($request->validated());

        return new DefaultPayloadResource($defaultPayload);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DefaultPayload $defaultPayload
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(DefaultPayload $defaultPayload): JsonResponse
    {
        $this->authorize('delete', $defaultPayload);

        $defaultPayload->delete();

        return response()->json([
            'message' => 'Default payload deleted successfully.'
        ]);
    }
}
