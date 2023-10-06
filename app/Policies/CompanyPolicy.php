<?php

namespace App\Policies;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any companies.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search companies');
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param User $user
     * @param Company $company
     *
     * @return bool
     */
    public function view(User $user, Company $company): bool
    {
        return $user->company->is($company) || $user->hasPermissionTo('read companies');
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create companies');
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param User $user
     * @param Company $company
     *
     * @return bool
     */
    public function update(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('update companies');
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param User $user
     * @param Company $company
     *
     * @return bool
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('delete companies');
    }
}
