<?php

namespace App\Policies;

use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can read any service logs.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read service-logs');
    }

    /**
     * Determine whether the user can show any service logs.
     *
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('search service-logs');
    }
}
