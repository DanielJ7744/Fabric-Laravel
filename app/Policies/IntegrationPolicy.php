<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\Integration;
use Illuminate\Auth\Access\HandlesAuthorization;

class IntegrationPolicy
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
        return $user->hasPermissionTo('search integrations');
    }

    /**
     * Determine whether the user can view the integration.
     *
     * @param User $user
     * @param Integration $integration
     *
     * @return bool
     */
    public function view(User $user, Integration $integration): bool
    {
        return $user->hasPermissionTo('read integrations');
    }

    /**
     * Determine whether the user can create integrations.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create integrations');
    }

    /**
     * Determine whether the user can update the integration.
     *
     * @param User $user
     * @param Integration $integration
     *
     * @return bool
     */
    public function update(User $user, Integration $integration): bool
    {
        return $user->hasPermissionTo('update integrations');
    }

    /**
     * Determine whether the user can delete the integration.
     *
     * @param User $user
     * @param Integration $integration
     *
     * @return bool
     */
    public function delete(User $user, Integration $integration): bool
    {
        return $user->hasPermissionTo('delete integrations');
    }
}
