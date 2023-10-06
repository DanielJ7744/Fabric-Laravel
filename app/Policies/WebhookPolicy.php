<?php

namespace App\Policies;

use App\Models\Fabric\Webhook;
use App\Models\Fabric\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any webhooks.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('search webhooks');
    }

    /**
     * Determine whether the user can view the webhook.
     *
     * @param User $user
     * @param Webhook $webhook
     *
     * @return bool
     */
    public function view(User $user, Webhook $webhook): bool
    {
        return $user->hasPermissionTo('read webhooks');
    }

    /**
     * Determine whether the user can create webhooks.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create webhooks');
    }

    /**
     * Determine whether the user can update the webhook.
     *
     * @param User $user
     * @param Webhook $webhook
     *
     * @return bool
     */
    public function update(User $user, Webhook $webhook): bool
    {
        return $user->hasPermissionTo('update webhooks');
    }

    /**
     * Determine whether the user can delete the webhook.
     *
     * @param User $user
     * @param Webhook $webhook
     *
     * @return bool
     */
    public function delete(User $user, Webhook $webhook): bool
    {
        return $user->hasPermissionTo('delete webhooks');
    }
}
