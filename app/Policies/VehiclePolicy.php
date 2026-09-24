<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    /**
     * Determine whether the user can view the vehicle list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('vehicles.view');
    }

    /**
     * Determine whether the user can view the given vehicle.
     */
    public function view(User $user, Vehicle $model): bool
    {
        return $user->can('vehicles.view');
    }

    /**
     * Determine whether the user can create vehicles.
     */
    public function create(User $user): bool
    {
        return $user->can('vehicles.create');
    }

    /**
     * Determine whether the user can update the given vehicle.
     */
    public function update(User $user, Vehicle $model): bool
    {
        return $user->can('vehicles.edit');
    }

    /**
     * Determine whether the user can delete the given vehicle.
     */
    public function delete(User $user, Vehicle $model): bool
    {
        return $user->can('vehicles.delete');
    }
}