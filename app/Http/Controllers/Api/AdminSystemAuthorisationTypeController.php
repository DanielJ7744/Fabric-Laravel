<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\SystemAuthorisationTypeResource;
use App\Http\Requests\Api\AdminStoreSystemAuthorisationTypeRequest;
use App\Http\Requests\Api\AdminUpdateSystemAuthorisationTypeRequest;

class AdminSystemAuthorisationTypeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreSystemAuthorisationTypeRequest $request
     *
     * @return SystemAuthorisationTypeResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreSystemAuthorisationTypeRequest $request): SystemAuthorisationTypeResource
    {
        $this->authorize('create', SystemAuthorisationType::class);

        $systemAuthorisationType = SystemAuthorisationType::create($request->validated());

        return new SystemAuthorisationTypeResource($systemAuthorisationType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateSystemAuthorisationTypeRequest $request
     * @param SystemAuthorisationType $systemAuthorisationType
     *
     * @return SystemAuthorisationTypeResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateSystemAuthorisationTypeRequest $request, SystemAuthorisationType $systemAuthorisationType): SystemAuthorisationTypeResource
    {
        $this->authorize('update', $systemAuthorisationType);

        $systemAuthorisationType->update($request->validated());

        return new SystemAuthorisationTypeResource($systemAuthorisationType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SystemAuthorisationType $systemAuthorisationType
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(SystemAuthorisationType $systemAuthorisationType): JsonResponse
    {
        $this->authorize('delete', $systemAuthorisationType);

        $systemAuthorisationType->delete();

        return response()->json([
            'message' => 'System authorisation type deleted successfully.'
        ]);
    }
}
