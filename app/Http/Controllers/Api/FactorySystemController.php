<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreFactorySystemRequest;
use App\Http\Resources\FactorySystemResource;
use App\Models\Fabric\FactorySystem;
use App\Queries\FactorySystemQuery;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FactorySystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FactorySystemQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, FactorySystemQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', FactorySystem::class);

        $factorySystems = $query->paginate($request->perPage);

        return FactorySystemResource::collection($factorySystems);
    }

    /**
     * Display the specified resource.
     *
     * @param FactorySystem $factorySystem
     * @param FactorySystemQuery $query
     *
     * @return FactorySystemResource
     *
     * @throws AuthorizationException
     */
    public function show(FactorySystem $factorySystem, FactorySystemQuery $query): FactorySystemResource
    {
        $this->authorize('view', $factorySystem);

        $factorySystem = $query->whereKey($factorySystem)->firstOrFail();

        return new FactorySystemResource($factorySystem);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFactorySystemRequest $request
     *
     * @return FactorySystemResource
     *
     * @throws AuthorizationException
     */
    public function store(StoreFactorySystemRequest $request): FactorySystemResource
    {
        $this->authorize('create', [FactorySystem::class, $request->validated()]);

        $factorySystem = FactorySystem::create($request->validated());

        return new FactorySystemResource($factorySystem);
    }
}
