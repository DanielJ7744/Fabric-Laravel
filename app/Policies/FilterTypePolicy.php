<?php

namespace App\Policies;

use App\Models\Fabric\Entity;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilterTypePolicy
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
        return $user->hasPermissionTo('search filter-types');
    }

    /**
     * Determine whether the user can view the entity.
     *
     * @param User $user
     * @param FilterType $filterType
     *
     * @return bool
     */
    public function view(User $user, FilterType $filterType): bool
    {
        return $user->hasPermissionTo('read filter-types');
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
        return $user->hasPermissionTo('create filter-types');
    }

    /**
     * Determine whether the user can update the entity.
     *
     * @param User $user
     * @param FilterType $filterType
     *
     * @return bool
     */
    public function update(User $user, FilterType $filterType): bool
    {
        return $user->hasPermissionTo('update filter-types');
    }

    /**
     * Determine whether the user can delete the entity.
     *
     * @param User $user
     * @param FilterType $filterType
     *
     * @return bool
     */
    public function delete(User $user, FilterType $filterType): bool
    {
        return $user->hasPermissionTo('delete filter-types');
    }
}
