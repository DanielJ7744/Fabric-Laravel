<?php

namespace App\Policies;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any alert groups.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('search alert-groups');
    }

    /**
     * Determine whether the user can view the alert groups.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Alerting\AlertGroups  $alertGroup
     * @return mixed
     */
    public function view(User $user, AlertGroups $alertGroup)
    {
        return $user->hasPermissionTo('read alert-groups');
    }

    /**
     * Determine whether the user can create alert groups.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create alert-groups');
    }

    /**
     * Determine whether the user can update the alert groups.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Alerting\AlertGroups  $alertGroup
     * @return mixed
     */
    public function update(User $user, AlertGroups $alertGroup)
    {
        return $user->hasPermissionTo('update alert-groups');
    }

    /**
     * Determine whether the user can delete the alert groups.
     *
     * @param  \App\Models\Fabric\User  $user
     * @param  \App\Models\Alerting\AlertGroups  $alertGroup
     * @return mixed
     */
    public function delete(User $user, AlertGroups $alertGroup)
    {
        return $user->hasPermissionTo('delete alert-groups');
    }
}
