<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\AuthorizationException;

class PatchworksUserCompanyController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param Company $company
     *
     * @return UserResource
     *
     * @throws AuthorizationException
     */
    public function update(User $user, Company $company): UserResource
    {
        $this->authorize('update', $user);

        User::withoutEvents(fn () => $company->users()->save($user));

        return new UserResource($user);
    }
}
