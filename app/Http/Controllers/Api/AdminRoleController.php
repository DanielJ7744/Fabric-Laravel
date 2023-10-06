<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminStoreRoleRequest;
use App\Http\Requests\Api\AdminUpdateRoleRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AdminRoleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreRoleRequest $request
     *
     * @return RoleResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreRoleRequest $request): RoleResource
    {
        $this->authorize('create', Role::class);

        $role = Role::create($request->validated());

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateRoleRequest $request
     * @param Role $role
     *
     * @return RoleResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateRoleRequest $request, Role $role): RoleResource
    {
        $this->authorize('update', $role);

        $role->update($request->validated());

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully.'
        ]);
    }
}
