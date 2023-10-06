<?php

namespace App\Policies;

use App\Models\Fabric\Entity;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EntityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any entities.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search entities');
    }

    /**
     * Determine whether the user can view the entity.
     *
     * @param User $user
     * @param Entity $entity
     *
     * @return bool
     */
    public function view(User $user, Entity $entity): bool
    {
        return $user->hasPermissionTo('read entities');
    }

    /**
     * Determine whether the user can create entities.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create entities');
    }

    /**
     * Determine whether the user can update the entity.
     *
     * @param User $user
     * @param Entity $entity
     *
     * @return Response
     */
    public function update(User $user, Entity $entity): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('update entities')) {
            return Response::allow();
        }

        return !is_null($entity->integration_id) && $user->hasPermissionTo('update entities')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the entity.
     *
     * @param User $user
     * @param Entity $entity
     *
     * @return Response
     */
    public function delete(User $user, Entity $entity): Response
    {
        if ($user->isPatchworksAdmin() && $user->hasPermissionTo('delete entities')) {
            return Response::allow();
        }

        return !is_null($entity->integration_id) && $user->hasPermissionTo('delete entities')
            ? Response::allow()
            : Response::deny();
    }
}
