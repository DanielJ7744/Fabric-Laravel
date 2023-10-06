<?php

namespace App\Http\Controllers\Api;

use App\Queries\RoleQuery;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param RoleQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, RoleQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);

        $roles = $query
            ->when(!auth()->user()->isPatchworksUser(), function ($query) {
                $query->where('patchworks_role', 0);
            })
            ->paginate();

        return RoleResource::collection($roles);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @param RoleQuery $query
     *
     * @return RoleResource
     *
     * @throws AuthorizationException
     */
    public function show(Role $role, RoleQuery $query): RoleResource
    {
        $this->authorize('view', $role);

        $role = $query
            ->whereKey($role)
            ->when(!auth()->user()->isPatchworksUser(), function ($query) {
                $query->where('patchworks_role', 0);
            })
            ->firstOrFail();

        return new RoleResource($role);
    }
}
