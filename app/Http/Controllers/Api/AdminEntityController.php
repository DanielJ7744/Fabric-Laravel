<?php

namespace App\Http\Controllers\Api;

use App\Queries\EntityQuery;
use Illuminate\Http\Request;
use App\Models\Fabric\Entity;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntityResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreEntityRequest;
use App\Http\Requests\Api\AdminUpdateEntityRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param EntityQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, EntityQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Entity::class);

        $entities = $query->paginate($request->perPage);

        return EntityResource::collection($entities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreEntityRequest $request
     *
     * @return EntityResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreEntityRequest $request): EntityResource
    {
        $this->authorize('create', Entity::class);

        $entity = Entity::create($request->validated());

        return new EntityResource($entity);
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
     * Update the specified resource in storage.
     *
     * @param AdminUpdateEntityRequest $request
     * @param Entity $entity
     *
     * @return EntityResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateEntityRequest $request, Entity $entity): EntityResource
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
