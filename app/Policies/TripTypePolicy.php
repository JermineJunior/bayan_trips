<?php

namespace App\Policies;

use App\Models\TripType;
use App\Models\User;

class TripTypePolicy
{
    /**
     * Determine whether the user can view the trip type list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('trip_types.view');
    }

    /**
     * Determine whether the user can view the given trip type.
     */
    public function view(User $user, TripType $model): bool
    {
        return $user->can('trip_types.view');
    }

    /**
     * Determine whether the user can create trip types.
     */
    public function create(User $user): bool
    {
        return $user->can('trip_types.create');
    }

    /**
     * Determine whether the user can update the given trip type.
     */
    public function update(User $user, TripType $model): bool
    {
        return $user->can('trip_types.edit');
    }

    /**
     * Determine whether the user can delete the given trip type.
     *
     * The default trip type can never be removed, even by admins.
     */
    public function delete(User $user, TripType $model): bool
    {
        return ! $model->is_default && $user->can('trip_types.delete');
    }
}