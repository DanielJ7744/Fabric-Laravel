<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Laravel\Passport\Client;

class OauthClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any oauth clients.
     *
     * @param  User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('search oauth-clients');
    }

    /**
     * Determine whether the user can create oauth clients.
     *
     * @param  User $user
     * @param  Connector $connector
     * @return mixed
     */
    public function create(User $user, Connector $connector): Response
    {
        if (!$user->hasPermissionTo('create oauth-clients')) {
            return Response::deny('You do not have permission to create an OAuth2 client.');
        }

        return $connector->clients()->count() < 2
            ? Response::allow()
            : Response::deny("Your have reached your subscription limit of 2 OAuth2 clients per endpoint.");
    }

    /**
     * Determine whether the user can update the oauth client.
     *
     * @param  User $user
     * @param  Client  $client
     * @param  Connector  $connector
     * @return mixed
     */
    public function update(User $user, Client $client, Connector $connector)
    {
        return $this->clientBelongsToConnector($client, $connector) && $user->hasPermissionTo('update oauth-clients');
    }

    /**
     * Determine whether the user can delete the oauth client.
     *
     * @param  User $user
     * @param  Client  $client
     * @param  Connector  $connector
     * @return mixed
     */
    public function delete(User $user, Client $client, Connector $connector)
    {
        return $this->clientBelongsToConnector($client, $connector) && $user->hasPermissionTo('delete oauth-clients');
    }

    /**
     * Determine whether the client belongs to the connector.
     *
     * @param  Client  $client
     * @param  Connector $connector
     * @return bool
     */
    private function clientBelongsToConnector(Client $client, Connector $connector): bool
    {
        return $connector->clients()->where('oauth_clients.id', $client->id)->exists();
    }
}
