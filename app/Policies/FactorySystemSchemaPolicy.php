<?php

namespace App\Policies;

use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Fabric\FactorySystemSchema;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FactorySystemSchemaPolicy
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
        return $user->hasPermissionTo('search factory-system-schemas');
    }

    /**
     * Determine whether the user can view the factory system.
     *
     * @param User $user
     * @param FactorySystemSchema $factorySystemSchema
     *
     * @return bool
     */
    public function view(User $user, FactorySystemSchema $factorySystemSchema): bool
    {
        return $user->hasPermissionTo('read factory-system-schemas');
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
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('create factory-system-schemas')) {
            return Response::allow();
        }

        $matchingIntegration = Integration::find($requestAttributes['integration_id']);

        return !is_null($matchingIntegration) && $user->hasPermissionTo('create factory-system-schemas')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param User $user
     * @param FactorySystemSchema $factorySystemSchema
     *
     * @return Response
     */
    public function update(User $user, FactorySystemSchema $factorySystemSchema): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('update factory-system-schemas')) {
            return Response::allow();
        }

        return !is_null($factorySystemSchema->integration_id) && $user->hasPermissionTo('update factory-system-schemas')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param User $user
     * @param FactorySystemSchema $factorySystemSchema
     *
     * @return Response
     */
    public function delete(User $user, FactorySystemSchema $factorySystemSchema): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('delete factory-system-schemas')) {
            return Response::allow();
        }

        return !is_null($factorySystemSchema->integration_id) && $user->hasPermissionTo('delete factory-system-schemas')
            ? Response::allow()
            : Response::deny();
    }
}
