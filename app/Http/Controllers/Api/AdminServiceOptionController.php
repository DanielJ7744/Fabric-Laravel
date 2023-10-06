<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Models\Fabric\ServiceOption;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceOptionResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreServiceOptionRequest;
use App\Http\Requests\Api\AdminUpdateServiceOptionRequest;

class AdminServiceOptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreServiceOptionRequest $request
     *
     * @return ServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreServiceOptionRequest $request): ServiceOptionResource
    {
        $this->authorize('create', ServiceOption::class);

        $serviceOption = ServiceOption::create($request->validated());

        return new ServiceOptionResource($serviceOption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateServiceOptionRequest $request
     * @param ServiceOption $serviceOption
     *
     * @return ServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateServiceOptionRequest $request, ServiceOption $serviceOption): ServiceOptionResource
    {
        $this->authorize('update', $serviceOption);

        $serviceOption->update($request->validated());

        return new ServiceOptionResource($serviceOption);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServiceOption $serviceOption
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(ServiceOption $serviceOption): JsonResponse
    {
        $this->authorize('delete', $serviceOption);

        $serviceOption->delete();

        return response()->json([
            'message' => 'Service option deleted successfully.'
        ]);
    }
}
