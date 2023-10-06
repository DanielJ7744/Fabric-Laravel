<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\Mapping;
use Illuminate\Auth\Access\HandlesAuthorization;

class MappingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any payment maps.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search mappings');
    }

    /**
     * Determine whether the user can view the payment maps.
     *
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('read mappings');
    }

    /**
     * Determine whether the user can create payment maps.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create mappings');
    }

    /**
     * Determine whether the user can update the payment maps.
     *
     * @param User $user
     * @param Mapping $mapping
     *
     * @return bool
     */
    public function update(User $user, Mapping $mapping): bool
    {
        return $user->hasPermissionTo('update mappings');
    }

    /**
     * Determine whether the user can delete the payment maps.
     *
     * @param User $user
     * @param Mapping $mapping
     *
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete mappings');
    }
}
