<?php

namespace App\Http\Controllers\Api;

use App\Queries\DefaultPayloadQuery;
use App\Http\Controllers\Controller;
use App\Models\Fabric\DefaultPayload;
use App\Http\Resources\DefaultPayloadResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DefaultPayloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DefaultPayloadQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(DefaultPayloadQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', DefaultPayload::class);

        $defaultPayloads = $query->get();

        return DefaultPayloadResource::collection($defaultPayloads);
    }

    /**
     * Display the specified resource.
     *
     * @param DefaultPayload $defaultPayload
     * @param DefaultPayloadQuery $query
     *
     * @return DefaultPayloadResource
     *
     * @throws AuthorizationException
     */
    public function show(DefaultPayload $defaultPayload, DefaultPayloadQuery $query): DefaultPayloadResource
    {
        $this->authorize('view', $defaultPayload);

        $defaultPayload = $query
            ->whereKey($defaultPayload)
            ->firstOrFail();

        return new DefaultPayloadResource($defaultPayload);
    }
}
