<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\ServiceOption;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceOptionPolicy
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
        return $user->hasPermissionTo('search service-options');
    }

    /**
     * Determine whether the user can view the integration.
     *
     * @param User $user
     * @param ServiceOption $serviceOption
     *
     * @return bool
     */
    public function view(User $user, ServiceOption $serviceOption): bool
    {
        return $user->hasPermissionTo('read service-options');
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
        return $user->hasPermissionTo('create service-options');
    }

    /**
     * Determine whether the user can update the integration.
     *
     * @param User $user
     * @param ServiceOption $serviceOption
     *
     * @return bool
     */
    public function update(User $user, ServiceOption $serviceOption): bool
    {
        return $user->hasPermissionTo('update service-options');
    }

    /**
     * Determine whether the user can delete the integration.
     *
     * @param User $user
     * @param ServiceOption $serviceOption
     *
     * @return bool
     */
    public function delete(User $user, ServiceOption $serviceOption): bool
    {
        return $user->hasPermissionTo('delete service-options');
    }
}
