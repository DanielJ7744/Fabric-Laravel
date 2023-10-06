<?php

namespace App\Policies;

use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any integrations.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search users');
    }

    /**
     * Determine whether the user can view the integration.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('read users');
    }

    /**
     * Determine whether the user can create integrations.
     *
     * @param User $user
     *
     * @return Response
     */
    public function create(User $user): Response
    {
        $currentCompany = $user->company;
        $activeUsers = $currentCompany->subscriptionUsage()->active_users;
        $maximumActiveUsers = $currentCompany->subscriptionAllowance()->users;

        if ($activeUsers > $maximumActiveUsers) {
            return Response::deny('You have reached your active user limit. Please upgrade your subscription.');
        }

        return $user->hasPermissionTo('create users')
            ? Response::allow('You have permission to create a user.')
            : Response::deny('You do not have permission to create a user.');
    }

    /**
     * Determine whether the user can update the integration.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('update users');
    }

    /**
     * Determine whether the user can delete the integration.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('delete users');
    }
}
