<?php

namespace App\Policies;

use App\Models\Fabric\SystemType;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create system types.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create system-types');
    }

    /**
     * Determine whether the user can update the system type.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Fabric\SystemType  $systemType
     * @return mixed
     */
    public function update(User $user, SystemType $systemType)
    {
        return $user->hasPermissionTo('update system-types');
    }
}
