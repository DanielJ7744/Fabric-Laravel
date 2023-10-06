<?php

namespace App\Policies;

use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InboundEndpointPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any inbound endpoints.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('search inbound-endpoints');
    }

    /**
     * Determine whether the user can view the inbound endpoint.
     *
     * @param  User  $user
     * @param  InboundEndpoint  $inboundEndpoint
     * @return mixed
     */
    public function view(User $user, InboundEndpoint $inboundEndpoint)
    {
        return $user->hasPermissionTo('read inbound-endpoints');
    }

    /**
     * Determine whether the user can create inbound endpoints.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isPatchworksAdmin()) {
            return Response::allow();
        }

        if (!$user->hasPermissionTo('create inbound-endpoints')) {
            return Response::deny('You do not have permission to create inbound endpoints.');
        }

        $usage = $user->company->subscriptionUsage();
        $allowance = $user->company->subscriptionAllowance();

        return $usage->inbound_apis < $allowance->api_keys
            ? Response::allow()
            : Response::deny("Your have reached your subscription limit of $allowance->api_keys inbound apis.");
    }

    /**
     * Determine whether the user can update the inbound endpoint.
     *
     * @param  User  $user
     * @param  InboundEndpoint  $inboundEndpoint
     * @return mixed
     */
    public function update(User $user, InboundEndpoint $inboundEndpoint)
    {
        return $user->hasPermissionTo('update inbound-endpoints');
    }

    /**
     * Determine whether the user can delete the inbound endpoint.
     *
     * @param  User  $user
     * @param  InboundEndpoint  $inboundEndpoint
     * @return mixed
     */
    public function delete(User $user, InboundEndpoint $inboundEndpoint)
    {
        return $user->hasPermissionTo('delete inbound-endpoints');
    }
}
