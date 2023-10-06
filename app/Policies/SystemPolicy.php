<?php

namespace App\Policies;

use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any systems.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('search systems');
    }

    /**
     * Determine whether the user can view the system.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Fabric\System $system
     * @return mixed
     */
    public function view(User $user, System $system)
    {
        return $user->hasPermissionTo('read systems');
    }

    /**
     * Determine whether the user can create systems.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create systems');
    }

    /**
     * Determine whether the user can update the system.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Fabric\System $system
     * @return mixed
     */
    public function update(User $user, System $system)
    {
        return $user->hasPermissionTo('update systems');
    }

    /**
     * Determine whether the user can delete the system.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Fabric\System $system
     * @return mixed
     */
    public function delete(User $user, System $system)
    {
        return $user->hasPermissionTo('delete systems');
    }
}
