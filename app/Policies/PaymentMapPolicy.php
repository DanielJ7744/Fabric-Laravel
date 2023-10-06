<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Tapestry\PaymentMap;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentMapPolicy
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
        return $user->hasPermissionTo('search payment-maps');
    }

    /**
     * Determine whether the user can view the payment maps.
     *
     * @param User $user
     * @param PaymentMap $paymentMap
     *
     * @return bool
     */
    public function view(User $user, PaymentMap $paymentMap): bool
    {
        return $user->hasPermissionTo('read payment-maps');
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
        return $user->hasPermissionTo('create payment-maps');
    }

    /**
     * Determine whether the user can update the payment maps.
     *
     * @param User $user
     * @param PaymentMap $paymentMap
     *
     * @return bool
     */
    public function update(User $user, PaymentMap $paymentMap): bool
    {
        return $user->hasPermissionTo('update payment-maps');
    }

    /**
     * Determine whether the user can delete the payment maps.
     *
     * @param User $user
     * @param PaymentMap $paymentMap
     *
     * @return bool
     */
    public function delete(User $user, PaymentMap $paymentMap): bool
    {
        return $user->hasPermissionTo('delete payment-maps');
    }
}
