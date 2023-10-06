<?php

namespace App\Policies;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventLogPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before($user, $ability)
    {
        if ($user->isPatchworksUser()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any event logs.
     *
     * @param  \App\Models\Fabric\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('search event-logs');
    }
}
