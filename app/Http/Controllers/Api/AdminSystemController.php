<?php

namespace App\Http\Controllers\Api;

use App\Events\SystemWasCreated;
use App\Models\Fabric\System;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SystemResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreSystemRequest;
use App\Http\Requests\Api\AdminUpdateSystemRequest;

class AdminSystemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreSystemRequest $request
     *
     * @return SystemResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreSystemRequest $request): SystemResource
    {
        $this->authorize('create', System::class);

        $system = System::create($request->validated());

        $system->addMediaFromRequest('image')->toMediaCollection('logo');

        event(new SystemWasCreated($system));

        return new SystemResource($system);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateSystemRequest $request
     * @param System $system
     *
     * @return SystemResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateSystemRequest $request, System $system): SystemResource
    {
        $this->authorize('update', $system);

        $system->update($request->validated());

        if ($request->hasFile('image')) {
            $system->addMediaFromRequest('image')->toMediaCollection('logo');
        }

        return new SystemResource($system);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param System $system
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(System $system): JsonResponse
    {
        $this->authorize('delete', $system);

        $system->delete();

        return response()->json([
            'message' => 'System deleted successfully.'
        ]);
    }
}
