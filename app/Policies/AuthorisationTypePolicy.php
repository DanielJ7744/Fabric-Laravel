<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\AuthorisationType;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorisationTypePolicy
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
        return $user->hasPermissionTo('read authorisation-types');
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param User $user
     * @param AuthorisationType $authorisationType
     *
     * @return bool
     */
    public function view(User $user, AuthorisationType $authorisationType): bool
    {
        return $user->hasPermissionTo('search authorisation-types');
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
        return $user->hasPermissionTo('create authorisation-types');
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param User $user
     * @param AuthorisationType $authorisationType
     *
     * @return bool
     */
    public function update(User $user, AuthorisationType $authorisationType): bool
    {
        return $user->hasPermissionTo('update authorisation-types');
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param User $user
     * @param AuthorisationType $authorisationType
     *
     * @return bool
     */
    public function delete(User $user, AuthorisationType $authorisationType): bool
    {
        return $user->hasPermissionTo('delete authorisation-types');
    }
}
