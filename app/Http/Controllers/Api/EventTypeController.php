<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventTypeResource;
use App\Models\Fabric\EventType;
use App\Queries\EventTypeQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param EventTypeQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(EventTypeQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', EventType::class);

        $eventTypes = $query->get();

        return EventTypeResource::collection($eventTypes);
    }

    /**
     * Display the specified resource.
     *
     * @param EventType $eventType
     * @param EventTypeQuery $query
     *
     * @return EventTypeResource
     *
     * @throws AuthorizationException
     */
    public function show(EventType $eventType, EventTypeQuery $query): EventTypeResource
    {
        $this->authorize('view', $eventType);

        $model = $query->whereKey($eventType)->firstOrFail();

        return new EventTypeResource($model);
    }
}
