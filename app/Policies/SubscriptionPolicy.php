<?php

namespace App\Policies;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use App\Models\Fabric\Subscription;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can read any subscriptions.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read subscriptions');
    }

    /**
     * Determine whether the user can show any subscriptions.
     *
     * @param User $user
     * @param Subscription $subscription
     *
     * @return bool
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->hasPermissionTo('search subscriptions');
    }

    /**
     * Authorize the assigning of a role to a user
     *
     * @param User $user
     * @param Subscription $subscription
     *
     * @return bool
     */
    public function add(User $user, Subscription $subscription): bool
    {
        return $user->hasPermissionTo('add subscription');
    }

    /**
     * Authorise that a user can remove a role
     *
     * @param User $user
     * @param Subscription $subscription
     *
     * @return bool
     */
    public function remove(User $user, Subscription $subscription): bool
    {
        return $user->hasPermissionTo('remove subscription');
    }
}
