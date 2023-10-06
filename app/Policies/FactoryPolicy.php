<?php

namespace App\Policies;

use App\Models\Fabric\Factory;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FactoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any filter template.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search factories');
    }

    /**
     * Determine whether the user can view the filter template.
     *
     * @param User $user
     * @param Factory $factory
     *
     * @return bool
     */
    public function view(User $user, Factory $factory): bool
    {
        return $user->hasPermissionTo('read factories');
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
        return $user->hasPermissionTo('create factories');
    }

    /**
     * Determine whether the user can update the entity.
     *
     * @param User $user
     * @param Factory $factory
     *
     * @return bool
     */
    public function update(User $user, Factory $factory): bool
    {
        return $user->hasPermissionTo('update factories');
    }

    /**
     * Determine whether the user can delete the entity.
     *
     * @param User $user
     * @param Factory $factory
     *
     * @return bool
     */
    public function delete(User $user, Factory $factory): bool
    {
        return $user->hasPermissionTo('delete factories');
    }
}
