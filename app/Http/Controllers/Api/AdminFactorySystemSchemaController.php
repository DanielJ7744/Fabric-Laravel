<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\FactorySystem;
use App\Queries\FactorySystemSchemaQuery;
use App\Models\Fabric\FactorySystemSchema;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\FactorySystemSchemaResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Api\AdminStoreFactorySystemSchemaRequest;
use App\Http\Requests\Api\AdminUpdateFactorySystemSchemaRequest;

class AdminFactorySystemSchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FactorySystemSchemaQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(FactorySystemSchemaQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FactorySystemSchema::class);

        $schemas = $query->paginate();

        return FactorySystemSchemaResource::collection($schemas);
    }

    /**
     * Display the specified resource.
     *
     * @param FactorySystemSchema $factorySystemSchema
     * @param FactorySystemSchemaQuery $query
     *
     * @return FactorySystemSchemaResource
     *
     * @throws AuthorizationException
     */
    public function show(FactorySystemSchema $factorySystemSchema, FactorySystemSchemaQuery $query): FactorySystemSchemaResource
    {
        $this->authorize('view', $factorySystemSchema);

        $factorySystemSchema = $query->whereKey($factorySystemSchema)->firstOrFail();

        return new FactorySystemSchemaResource($factorySystemSchema);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFactorySystemSchemaRequest $request
     *
     * @return FactorySystemSchemaResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFactorySystemSchemaRequest $request): FactorySystemSchemaResource
    {
        $this->authorize('create', [FactorySystemSchema::class, $request->validated()]);

        $factorySystemSchema = FactorySystemSchema::create($request->validated());

        return new FactorySystemSchemaResource($factorySystemSchema);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFactorySystemSchemaRequest $request
     * @param FactorySystemSchema $factorySystemSchema
     *
     * @return FactorySystemSchemaResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFactorySystemSchemaRequest $request, FactorySystemSchema $factorySystemSchema): FactorySystemSchemaResource
    {
        $this->authorize('update', $factorySystemSchema);

        $factorySystemSchema->update($request->validated());

        return new FactorySystemSchemaResource($factorySystemSchema);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FactorySystemSchema $factorySystemSchema
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FactorySystemSchema $factorySystemSchema): JsonResponse
    {
        $this->authorize('delete', $factorySystemSchema);

        $factorySystemSchema->delete();

        return response()->json([
            'message' => 'Factory system schema deleted successfully.'
        ]);
    }
}
