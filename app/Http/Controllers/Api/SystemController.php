<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemResource;
use App\Models\Fabric\System;
use App\Queries\SystemQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Queries\SystemQuery  $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(SystemQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', System::class);

        $systems = $query->get();

        return SystemResource::collection($systems);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Fabric\System  $system
     * @param \App\Queries\SystemQuery  $query
     * @return \App\Http\Resources\SystemResource
     */
    public function show(System $system, SystemQuery $query): SystemResource
    {
        $this->authorize('view', $system);

        $system = $query
            ->whereKey($system)
            ->firstOrFail();

        return new SystemResource($system);
    }
}
