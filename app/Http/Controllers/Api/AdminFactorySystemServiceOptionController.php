<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminStoreFactorySystemServiceOptionRequest;
use App\Http\Requests\Api\AdminUpdateFactorySystemServiceOptionRequest;
use App\Http\Resources\FactorySystemServiceOptionResource;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemServiceOption;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminFactorySystemServiceOptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFactorySystemServiceOptionRequest $request
     * @param FactorySystem $factorySystem
     *
     * @return FactorySystemServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function store(
        AdminStoreFactorySystemServiceOptionRequest $request,
        FactorySystem $factorySystem
    ): FactorySystemServiceOptionResource {
        $this->authorize('create', FactorySystemServiceOption::class);

        $factorySystemServiceOption = FactorySystemServiceOption::create(array_merge(
            $request->validated(),
            ['factory_system_id' => $factorySystem->id]
        ));

        return new FactorySystemServiceOptionResource($factorySystemServiceOption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFactorySystemServiceOptionRequest $request
     * @param FactorySystem $factorySystem
     * @param FactorySystemServiceOption $factorySystemServiceOption
     *
     * @return FactorySystemServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function update(
        AdminUpdateFactorySystemServiceOptionRequest $request,
        FactorySystem $factorySystem,
        FactorySystemServiceOption $factorySystemServiceOption
    ): FactorySystemServiceOptionResource {
        $this->authorize('update', $factorySystemServiceOption);

        $factorySystemServiceOption->update($request->validated());

        return new FactorySystemServiceOptionResource($factorySystemServiceOption);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FactorySystem $factorySystem
     * @param FactorySystemServiceOption $factorySystemServiceOption
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(
        FactorySystem $factorySystem,
        FactorySystemServiceOption $factorySystemServiceOption
    ): JsonResponse {
        $this->authorize('delete', $factorySystemServiceOption);

        $factorySystemServiceOption->delete();

        return response()->json([
            'message' => 'Factory system service option deleted successfully.'
        ]);
    }
}
