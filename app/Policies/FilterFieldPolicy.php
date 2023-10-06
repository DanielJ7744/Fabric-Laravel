<?php

namespace App\Policies;

use App\Models\Fabric\Entity;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilterFieldPolicy
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
        return $user->hasPermissionTo('search filter-fields');
    }

    /**
     * Determine whether the user can view the entity.
     *
     * @param User $user
     * @param FilterField $filterField
     *
     * @return bool
     */
    public function view(User $user, FilterField $filterField): bool
    {
        return $user->hasPermissionTo('read filter-fields');
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
        return $user->hasPermissionTo('create filter-fields');
    }

    /**
     * Determine whether the user can update the entity.
     *
     * @param User $user
     * @param FilterField $filterField
     *
     * @return bool
     */
    public function update(User $user, FilterField $filterField): bool
    {
        return $user->hasPermissionTo('update filter-fields');
    }

    /**
     * Determine whether the user can delete the entity.
     *
     * @param User $user
     * @param FilterField $filterField
     *
     * @return bool
     */
    public function delete(User $user, FilterField $filterField): bool
    {
        return $user->hasPermissionTo('delete filter-fields');
    }
}
