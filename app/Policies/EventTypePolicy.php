<?php

namespace App\Policies;

use App\Models\Fabric\EventType;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search system-event-types');
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param User $user
     * @param EventType $eventType
     *
     * @return bool
     */
    public function view(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('read system-event-types');
    }

    /**
     * Determine whether the user can create services.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create system-event-types');
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param User $user
     * @param EventType $eventType
     *
     * @return bool
     */
    public function update(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('update system-event-types');
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param User $user
     * @param EventType $eventType
     *
     * @return bool
     */
    public function delete(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('delete system-event-types');
    }
}
