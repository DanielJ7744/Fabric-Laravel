<?php

namespace App\Http\Controllers\Api;

use App\Models\Fabric\User;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\Integration;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\StoreIntegrationUserRequest;

class AdminIntegrationUserController extends Controller
{
    /**
     * Add the pivot record to attach a user to an integration
     *
     * @param StoreIntegrationUserRequest $request
     * @param Integration $integration
     *
     * @return JsonResponse
     */
    public function store(StoreIntegrationUserRequest $request, Integration $integration): JsonResponse
    {
        $integration->users()->syncWithoutDetaching($request->user_id);

        return response()->json([
            'message' => 'Integration user created successfully'
        ]);
    }

    /**
     * Remove the pivot record to remove a user from an integration
     *
     * @param Integration $integration
     * @param User $user
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Integration $integration, User $user): JsonResponse
    {
        $this->authorize('delete integration-users');

        $integration->users()->detach($user);

        return response()->json([
            'message' => 'Integration user deleted successfully.'
        ]);
    }
}
