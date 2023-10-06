<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use App\Queries\UserQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserQuery $query
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(UserQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $query->paginate();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     * @throws AuthorizationException
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $this->authorize('create', User::class);

        $user = Company::current()
            ->users()
            ->create($request->validated())
            ->assignRole('client user');

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param UserQuery $query
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(User $user, UserQuery $query): UserResource
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
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
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
     * @return JsonResponse
     * @throws ModelNotFoundException
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
