<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminUpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Fabric\User;
use App\Queries\AdminUserQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param AdminUserQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(AdminUserQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $query->paginate();

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param AdminUserQuery $query
     *
     * @return UserResource
     *
     * @throws AuthorizationException
     */
    public function show(User $user, AdminUserQuery $query): UserResource
    {
        $this->authorize('view', $user);

        $user = $query
            ->whereKey($user)
            ->firstOrFail();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateUserRequest $request
     * @param User $user
     *
     * @return UserResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateUserRequest $request, User $user): UserResource
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }

    /**
     * Restore a soft-deleted user
     *
     * @param $id
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function restore($id): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        return response()->json([
            'message' => 'User restored successfully'
        ]);
    }
}
