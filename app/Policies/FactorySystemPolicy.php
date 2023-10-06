<?php

namespace App\Policies;

use App\Models\Fabric\Entity;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Fabric\FactorySystem;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FactorySystemPolicy
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
        return $user->hasPermissionTo('search factory-systems');
    }

    /**
     * Determine whether the user can view the factory system.
     *
     * @param User $user
     * @param FactorySystem $factorySystem
     *
     * @return bool
     */
    public function view(User $user, FactorySystem $factorySystem): bool
    {
        return $user->hasPermissionTo('read factory-systems');
    }

    /**
     * Determine whether the user can create factory systems.
     *
     * @param User $user
     * @param array $requestAttributes
     *
     * @return Response
     */
    public function create(User $user, array $requestAttributes): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('create factory-systems')) {
            return Response::allow();
        }

        $matchingIntegration = Integration::find($requestAttributes['integration_id']);

        return $user->hasPermissionTo('create factory-systems') && !is_null($matchingIntegration)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the factory system.
     *
     * @param User $user
     * @param FactorySystem $factorySystem
     *
     * @return bool
     */
    public function update(User $user, FactorySystem $factorySystem): bool
    {
        return $user->hasPermissionTo('update factory-systems');
    }

    /**
     * Determine whether the user can delete the factory system.
     *
     * @param User $user
     * @param FactorySystem $factorySystem
     *
     * @return Response
     */
    public function delete(User $user, FactorySystem $factorySystem): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('delete factory-systems')) {
            return Response::allow('You are an admin.');
        }

        return $user->hasPermissionTo('delete factory-systems') && $factorySystem->integration_id !== null
            ? Response::allow()
            : Response::deny();
    }
}
