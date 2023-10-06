<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEntityRequest;
use App\Http\Requests\Api\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Fabric\Entity;
use App\Queries\EntityQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param EntityQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(EntityQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Entity::class);

        $entities = $query->get();

        return EntityResource::collection($entities);
    }

    /**
     * Display the specified resource.
     *
     * @param Entity $entity
     * @param EntityQuery $query
     *
     * @return EntityResource
     *
     * @throws AuthorizationException
     */
    public function show(Entity $entity, EntityQuery $query): EntityResource
    {
        $this->authorize('view', $entity);

        $entity = $query
            ->whereKey($entity)
            ->firstOrFail();

        return new EntityResource($entity);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEntityRequest $request
     *
     * @return EntityResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreEntityRequest $request): EntityResource
    {
        $this->authorize('create', Entity::class);

        $entity = Entity::create($request->validated());

        return new EntityResource($entity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEntityRequest $request
     * @param Entity $entity
     *
     * @return EntityResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateEntityRequest $request, Entity $entity): EntityResource
    {
        $this->authorize('update', $entity);

        $entity->update($request->validated());

        return new EntityResource($entity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Entity $entity
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Entity $entity): JsonResponse
    {
        $this->authorize('delete', $entity);

        $entity->delete();

        return response()->json([
            'message' => 'Entity deleted successfully.'
        ]);
    }
}
