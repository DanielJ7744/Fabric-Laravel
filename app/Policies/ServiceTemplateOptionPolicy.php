<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\ServiceTemplateOption;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTemplateOptionPolicy
{
    use HandlesAuthorization;

    /**
     * Authorize the index service template option request
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search service-template-options');
    }

    /**
     * Authorize the show service template option request
     *
     * @param User $user
     * @param ServiceTemplateOption $serviceTemplateOption
     *
     * @return bool
     */
    public function view(User $user, ServiceTemplateOption $serviceTemplateOption): bool
    {
        return $user->hasPermissionTo('read service-template-options');
    }

    /**
     * Authorize the create service template option request
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create service-template-options');
    }

    /**
     * Authorize the update service template option request
     *
     * @param User $user
     * @param ServiceTemplateOption $serviceTemplateOption
     *
     * @return bool
     */
    public function update(User $user, ServiceTemplateOption $serviceTemplateOption): bool
    {
        return $user->hasPermissionTo('update service-template-options');
    }

    /**
     * Authorize the delete service template option request
     *
     * @param User $user
     * @param ServiceTemplateOption $serviceTemplateOption
     *
     * @return bool
     */
    public function delete(User $user, ServiceTemplateOption $serviceTemplateOption): bool
    {
        return $user->hasPermissionTo('delete service-template-options');
    }
}
