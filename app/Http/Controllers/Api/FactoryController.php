<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactoryResource;
use App\Models\Fabric\Factory;
use App\Queries\FactoryQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FactoryQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, FactoryQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Factory::class);

        $factories = $query->paginate($request->perPage);

        return FactoryResource::collection($factories);
    }

    /**
     * Display the specified resource.
     *
     * @param Factory $factory
     * @param FactoryQuery $query
     *
     * @return FactoryResource
     *
     * @throws AuthorizationException
     */
    public function show(Factory $factory, FactoryQuery $query): FactoryResource
    {
        $this->authorize('view', $factory);

        $factory = $query->whereKey($factory)->firstOrFail();

        return new FactoryResource($factory);
    }
}
