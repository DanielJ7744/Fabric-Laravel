<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ConnectorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any connectors.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search connectors');
    }

    /**
     * Determine whether the user can view the connector.
     *
     * @param User $user
     * @param Connector $connector
     *
     * @return bool
     */
    public function view(User $user, Connector $connector): bool
    {
        return $user->hasPermissionTo('read connectors');
    }

    /**
     * Determine whether the user can create connectors.
     *
     * @param User $user
     *
     * @return Response
     */
    public function create(User $user): Response
    {
        $currentCompany = $user->company;
        $subscriptionAllowance = $currentCompany->subscriptionAllowance();
        if ($user->hasRole('patchworks admin')) {
            return Response::allow();
        }

        return $subscriptionAllowance->tiers->contains('Base') || !$user->hasPermissionTo('create connectors')
            ? Response::deny('You are not authorised to create a connector.')
            : Response::allow();
    }

    /**
     * Determine whether the user can update the connector.
     *
     * @param User $user
     * @param Connector $connector
     *
     * @return bool
     */
    public function update(User $user, Connector $connector): bool
    {
        return $user->hasPermissionTo('update connectors');
    }

    /**
     * Determine whether the user can delete the connector.
     *
     * @param User $user
     * @param Connector $connector
     *
     * @return bool
     */
    public function delete(User $user, Connector $connector): bool
    {
        return $user->hasPermissionTo('delete connectors');
    }
}
