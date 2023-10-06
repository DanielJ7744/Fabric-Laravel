<?php

namespace App\Http\Controllers\Api;

use App\Models\Fabric\Factory;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FactoryResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreFactoryRequest;
use App\Http\Requests\Api\AdminUpdateFactoryRequest;

class AdminFactoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFactoryRequest $request
     *
     * @return FactoryResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFactoryRequest $request): FactoryResource
    {
        $this->authorize('create', Factory::class);

        $factory = Factory::create($request->validated());

        return new FactoryResource($factory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFactoryRequest $request
     * @param Factory $factory
     *
     * @return FactoryResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFactoryRequest $request, Factory $factory): FactoryResource
    {
        $this->authorize('update', $factory);

        $factory->update($request->validated());

        return new FactoryResource($factory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Factory $factory
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Factory $factory): JsonResponse
    {
        $this->authorize('delete', $factory);

        $factory->delete();

        return response()->json([
            'message' => 'Factory deleted successfully.'
        ]);
    }
}
