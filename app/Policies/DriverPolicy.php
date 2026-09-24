<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    /**
     * Determine whether the user can view the driver list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('drivers.view');
    }

    /**
     * Determine whether the user can view the given driver.
     */
    public function view(User $user, Driver $model): bool
    {
        return $user->can('drivers.view');
    }

    /**
     * Determine whether the user can create drivers.
     */
    public function create(User $user): bool
    {
        return $user->can('drivers.create');
    }

    /**
     * Determine whether the user can update the given driver.
     */
    public function update(User $user, Driver $model): bool
    {
        return $user->can('drivers.edit');
    }

    /**
     * Determine whether the user can delete the given driver.
     */
    public function delete(User $user, Driver $model): bool
    {
        return $user->can('drivers.delete');
    }
}