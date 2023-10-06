<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemAuthorisationTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search system-authorisation-types');
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param User $user
     * @param SystemAuthorisationType $systemAuthorisationType
     *
     * @return bool
     */
    public function view(User $user, SystemAuthorisationType $systemAuthorisationType): bool
    {
        return $user->hasPermissionTo('read system-authorisation-types');
    }

    /**
     * Determine whether the user can create services.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create system-authorisation-types');
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param User $user
     * @param SystemAuthorisationType $systemAuthorisationType
     *
     * @return bool
     */
    public function update(User $user, SystemAuthorisationType $systemAuthorisationType): bool
    {
        return $user->hasPermissionTo('update system-authorisation-types');
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param User $user
     * @param SystemAuthorisationType $systemAuthorisationType
     *
     * @return bool
     */
    public function delete(User $user, SystemAuthorisationType $systemAuthorisationType): bool
    {
        return $user->hasPermissionTo('delete system-authorisation-types');
    }
}
