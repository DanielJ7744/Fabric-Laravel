<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Models\Fabric\FactorySystem;
use App\Http\Controllers\Controller;
use App\Http\Resources\FactorySystemResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreFactorySystemRequest;
use App\Http\Requests\Api\AdminUpdateFactorySystemRequest;

class AdminFactorySystemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFactorySystemRequest $request
     *
     * @return FactorySystemResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFactorySystemRequest $request): FactorySystemResource
    {
        $this->authorize('create', [FactorySystem::class, $request->validated()]);

        $factorySystem = FactorySystem::create($request->validated());

        return new FactorySystemResource($factorySystem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFactorySystemRequest $request
     * @param FactorySystem $factorySystem
     *
     * @return FactorySystemResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFactorySystemRequest $request, FactorySystem $factorySystem): FactorySystemResource
    {
        $this->authorize('update', $factorySystem);

        $factorySystem->update($request->validated());

        return new FactorySystemResource($factorySystem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FactorySystem $factorySystem
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FactorySystem $factorySystem): JsonResponse
    {
        $this->authorize('delete', $factorySystem);

        $factorySystem->delete();

        return response()->json([
            'message' => 'Factory system deleted successfully.'
        ]);
    }
}
