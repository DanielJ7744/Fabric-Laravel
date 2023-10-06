<?php

namespace App\Policies;

use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Fabric\ServiceTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServiceTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Authorize the index service template request
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search service-templates');
    }

    /**
     * Authorize the show service template request
     *
     * @param User $user
     * @param ServiceTemplate $serviceTemplate
     *
     * @return bool
     */
    public function view(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('read service-templates');
    }

    /**
     * Authorize the create service template request
     *
     * @param User $user
     * @param array $requestAttributes
     *
     * @return Response
     */
    public function create(User $user, array $requestAttributes): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('create service-templates')) {
            return Response::allow();
        }

        $matchingIntegration = Integration::find($requestAttributes['integration_id']);

        return $user->hasPermissionTo('create service-templates') && !is_null($matchingIntegration)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Authorize the update service template request
     *
     * @param User $user
     * @param ServiceTemplate $serviceTemplate
     *
     * @return bool
     */
    public function update(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('update service-templates');
    }

    /**
     * Authorize the delete service template request
     *
     * @param User $user
     * @param ServiceTemplate $serviceTemplate
     *
     * @return bool
     */
    public function delete(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('delete service-templates');
    }
}
