<?php

namespace App\Policies;

use App\Models\Fabric\User;
use App\Models\Tapestry\ShipmentMap;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShipmentMapPolicy
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
        return $user->hasPermissionTo('search shipment-maps');
    }

    /**
     * Determine whether the user can view the payment maps.
     *
     * @param User $user
     * @param ShipmentMap $shipmentMap
     *
     * @return bool
     */
    public function view(User $user, ShipmentMap $shipmentMap): bool
    {
        return $user->hasPermissionTo('read shipment-maps');
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
        return $user->hasPermissionTo('create shipment-maps');
    }

    /**
     * Determine whether the user can update the payment maps.
     *
     * @param User $user
     * @param ShipmentMap $shipmentMap
     *
     * @return bool
     */
    public function update(User $user, ShipmentMap $shipmentMap): bool
    {
        return $user->hasPermissionTo('update shipment-maps');
    }

    /**
     * Determine whether the user can delete the payment maps.
     *
     * @param User $user
     * @param ShipmentMap $shipmentMap
     *
     * @return bool
     */
    public function delete(User $user, ShipmentMap $shipmentMap): bool
    {
        return $user->hasPermissionTo('delete shipment-maps');
    }
}
