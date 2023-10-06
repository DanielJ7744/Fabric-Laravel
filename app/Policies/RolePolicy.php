<?php

namespace App\Policies;

use App\Models\Fabric\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Roles that are forbidden to non-Patchworks users
     *
     * @var array
     */
    private array $forbiddenRoles = ['patchworks user', 'patchworks admin'];

    /**
     * Authorize the assigning of a role to a user
     *
     * @param User $user
     * @param Role $role
     *
     * @return Response
     */
    public function add(User $user, Role $role): Response
    {
        if ($user->isPatchworksAdmin()) {
            return Response::allow('You are an admin.');
        }

        if (in_array($role->name, $this->forbiddenRoles)) {
            return Response::deny(sprintf('You are not authorised to add %s as it is forbidden.', $role->name));
        }

        if (!$user->hasRole('client admin') && !$user->hasRole('patchworks user')) {
            return Response::deny('You are not allowed to add a role.');
        }

        return Response::allow('You are allowed to add a role.');
    }

    /**
     * Authorise that a user can remove a role
     *
     * @param User $user
     * @param Role $role
     * @param User $userBeingModified
     *
     * @return Response
     */
    public function remove(User $user, Role $role, User $userBeingModified): Response
    {
        if ($user->isPatchworksAdmin() && !$user->is($userBeingModified)) {
            return Response::allow('You are an admin.');
        }

        if ($user->is($userBeingModified)) {
            return Response::deny('You cannot remove your role.');
        }

        if (in_array($role->name, $this->forbiddenRoles)) {
            return Response::deny(sprintf('You are not authorised to remove %s as it is forbidden.', $role->name));
        }

        if (!$user->hasRole('client admin') && !$user->hasRole('patchworks user')) {
            return Response::deny('You cannot remove this role.');
        }

        return Response::allow('You can remove this role.');
    }

    /**
     * Determine whether the user can view any companies.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read roles');
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param User $user
     * @param Role $role
     *
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('search roles');
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
        return $user->hasPermissionTo('create roles');
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param User $user
     * @param Role $role
     *
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update roles');
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param User $user
     * @param Role $role
     *
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete roles');
    }
}
