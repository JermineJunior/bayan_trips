<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    /**
     * Determine whether the user can view the trip list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('trips.view');
    }

    /**
     * Determine whether the user can view the given trip.
     */
    public function view(User $user, Trip $model): bool
    {
        return $user->can('trips.view');
    }

    /**
     * Determine whether the user can create trips.
     */
    public function create(User $user): bool
    {
        return $user->can('trips.create');
    }

    /**
     * Determine whether the user can update the given trip.
     */
    public function update(User $user, Trip $model): bool
    {
        return $user->can('trips.edit');
    }

    /**
     * Determine whether the user can delete the given trip.
     */
    public function delete(User $user, Trip $model): bool
    {
        return $user->can('trips.delete');
    }
}