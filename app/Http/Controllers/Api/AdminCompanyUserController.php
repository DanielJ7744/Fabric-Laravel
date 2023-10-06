<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminStoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\AuthorizationException;

class AdminCompanyUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Company $company
     * @param AdminStoreUserRequest $request
     *
     * @return UserResource
     *
     * @throws AuthorizationException
     */
    public function store(Company $company, AdminStoreUserRequest $request): UserResource
    {
        $this->authorize('create', User::class);

        $user = $company
            ->users()
            ->create($request->validated());

        return new UserResource($user);
    }
}
