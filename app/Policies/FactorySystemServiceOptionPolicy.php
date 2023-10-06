<?php

namespace App\Policies;

use App\Models\Fabric\FactorySystemServiceOption;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FactorySystemServiceOptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any factory system.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search factory-system-service-options');
    }

    /**
     * Determine whether the user can view the factory system.
     *
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('read factory-system-service-options');
    }

    /**
     * Determine whether the user can create factory systems.
     *
     * @param User $user
     * @param array $requestAttributes
     *
     * @return Response
     */
    public function create(User $user): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('create factory-system-service-options')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can update the factory system.
     *
     * @param User $user
     * @param FactorySystemServiceOption $factorySystemServiceOption
     *
     * @return Response
     */
    public function update(User $user, FactorySystemServiceOption $factorySystemServiceOption): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('update factory-system-service-options')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can delete the factory system.
     *
     * @param User $user
     * @param FactorySystemServiceOption $factorySystemServiceOption
     *
     * @return Response
     */
    public function delete(User $user, FactorySystemServiceOption $factorySystemServiceOption): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('delete factory-system-service-options')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
