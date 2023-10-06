<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactorySystemServiceOptionResource;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemServiceOption;
use App\Queries\FactorySystemServiceOptionQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FactorySystemServiceOptionController extends Controller
{
    /**
     * Get all FactorySystemServiceOptions
     *
     * @param FactorySystem $factorySystem
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(
        FactorySystem $factorySystem,
        FactorySystemServiceOptionQuery $factorySystemServiceOptionQuery
    ): AnonymousResourceCollection {
        $this->authorize('viewAny', FactorySystemServiceOption::class);

        $factorySystemServiceOptions = $factorySystemServiceOptionQuery->whereBelongsTo($factorySystem)->get();

        return FactorySystemServiceOptionResource::collection($factorySystemServiceOptions);
    }

    /**
     * Show a FactorySystemServiceOptions
     *
     * @param FactorySystem $factorySystem
     * @param FactorySystemServiceOption $factorySystemServiceOption
     *
     * @return FactorySystemServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function show(
        FactorySystem $factorySystem,
        FactorySystemServiceOption $factorySystemServiceOption,
        FactorySystemServiceOptionQuery $factorySystemServiceOptionQuery
    ): FactorySystemServiceOptionResource {
        $this->authorize('view', $factorySystemServiceOption);

        $factorySystemServiceOption = $factorySystemServiceOptionQuery
            ->whereBelongsTo($factorySystem)
            ->whereKey($factorySystemServiceOption)
            ->firstOrFail();

        return new FactorySystemServiceOptionResource($factorySystemServiceOption);
    }
}
