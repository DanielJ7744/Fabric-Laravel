<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\FilterTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilterTemplatePolicy
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
        return $user->hasPermissionTo('search filter-templates');
    }

    /**
     * Determine whether the user can view the filter template.
     *
     * @param User $user
     * @param FilterTemplate $filterTemplate
     *
     * @return bool
     */
    public function view(User $user, FilterTemplate $filterTemplate): bool
    {
        return $user->hasPermissionTo('read filter-templates');
    }

    /**
     * Determine whether the user can create filter templates.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create filter-templates');
    }

    /**
     * Determine whether the user can update the filter template.
     *
     * @param User $user
     * @param FilterTemplate $filterTemplate
     *
     * @return bool
     */
    public function update(User $user, FilterTemplate $filterTemplate): bool
    {
        return $user->hasPermissionTo('update filter-templates');
    }

    /**
     * Determine whether the user can delete the filter template.
     *
     * @param User $user
     * @param FilterTemplate $filterTemplate
     *
     * @return bool
     */
    public function delete(User $user, FilterTemplate $filterTemplate): bool
    {
        return $user->hasPermissionTo('delete filter-templates');
    }
}
