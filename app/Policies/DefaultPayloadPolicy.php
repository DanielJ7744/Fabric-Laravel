<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Fabric\DefaultPayload;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultPayloadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any systems.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search default-payloads');
    }

    /**
     * Determine whether the user can view the system.
     *
     * @param User $user
     * @param DefaultPayload $defaultPayload
     *
     * @return bool
     */
    public function view(User $user, DefaultPayload $defaultPayload): bool
    {
        return $user->hasPermissionTo('read default-payloads');
    }

    /**
     * Determine whether the user can create systems.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create default-payloads');
    }

    /**
     * Determine whether the user can update the system.
     *
     * @param User $user
     * @param DefaultPayload $defaultPayload
     *
     * @return bool
     */
    public function update(User $user, DefaultPayload $defaultPayload): bool
    {
        return $user->hasPermissionTo('update default-payloads');
    }

    /**
     * Determine whether the user can delete the system.
     *
     * @param User $user
     * @param DefaultPayload $defaultPayload
     *
     * @return bool
     */
    public function delete(User $user, DefaultPayload $defaultPayload): bool
    {
        return $user->hasPermissionTo('delete default-payloads');
    }
}
