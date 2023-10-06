<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search services');
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param User $user
     * @param Service $service
     *
     * @return bool
     */
    public function view(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('read services');
    }

    /**
     * Determine whether the user can create services.
     *
     * @param User $user
     *
     * @return Response
     */
    public function create(User $user): Response
    {
        $currentCompany = $user->company;
        $subscriptionAllowance = $currentCompany->subscriptionAllowance();

        return (!$user->hasRole('patchworks admin') && $subscriptionAllowance->tiers->contains('Base')) || !$user->hasPermissionTo('create services')
            ? Response::deny('You are not authorised to create a service.')
            : Response::allow('You are allowed to create a service.');
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param User $user
     * @param Service $service
     *
     * @return bool
     */
    public function update(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('update services');
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param User $user
     * @param Service $service
     *
     * @return bool
     */
    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('delete services');
    }
}
