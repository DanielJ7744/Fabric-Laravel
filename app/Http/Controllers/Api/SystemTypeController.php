<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSystemTypeRequest;
use App\Http\Requests\Api\UpdateSystemTypeRequest;
use App\Http\Resources\SystemTypeResource;
use App\Models\Fabric\SystemType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SystemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection

     */
    public function index(): AnonymousResourceCollection
    {
        return SystemTypeResource::collection(SystemType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\StoreSystemTypeRequest  $request
     * @return \App\Http\Resources\SystemTypeResource
     */
    public function store(StoreSystemTypeRequest $request): SystemTypeResource
    {
        $this->authorize('create', SystemType::class);

        $systemType = SystemType::create($request->all());

        return new SystemTypeResource($systemType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fabric\SystemType  $systemType
     * @return \App\Http\Resources\SystemTypeResource
     */
    public function show(SystemType $systemType): SystemTypeResource
    {
        return new SystemTypeResource($systemType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\UpdateSystemTypeRequest  $request
     * @param  \App\Models\Fabric\SystemType  $systemType
     * @return \App\Http\Resources\SystemTypeResource
     */
    public function update(UpdateSystemTypeRequest $request, SystemType $systemType): SystemTypeResource
    {
        $this->authorize('update', $systemType);

        $systemType->update($request->all());

        return new SystemTypeResource($systemType);
    }
}
