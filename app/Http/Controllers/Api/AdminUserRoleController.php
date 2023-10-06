<?php

namespace App\Http\Controllers\Api;

use App\Models\Fabric\User;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminUserRoleController extends Controller
{
    /**
     * Add a role to a user.
     *
     * @param User $user
     * @param Role $role
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(User $user, Role $role): JsonResponse
    {
        $this->authorize('add', $role);

        $user->assignRole($role);

        return response()->json([
            'message' => 'Roles assigned to user successfully.'
        ]);
    }

    /**
     * Remove a role from a user
     *
     * @param User $user
     * @param Role $role
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(User $user, Role $role): JsonResponse
    {
        $this->authorize('remove', [$role, $user]);

        $user->removeRole($role);

        return response()->json([
            'message' => 'Role removed from user successfully'
        ]);
    }
}
