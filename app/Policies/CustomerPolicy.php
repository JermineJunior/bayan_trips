<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Determine whether the user can view the customer list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('customers.view');
    }

    /**
     * Determine whether the user can view the given customer.
     */
    public function view(User $user, Customer $model): bool
    {
        return $user->can('customers.view');
    }

    /**
     * Determine whether the user can create customers.
     */
    public function create(User $user): bool
    {
        return $user->can('customers.create');
    }

    /**
     * Determine whether the user can update the given customer.
     */
    public function update(User $user, Customer $model): bool
    {
        return $user->can('customers.edit');
    }

    /**
     * Determine whether the user can delete the given customer.
     */
    public function delete(User $user, Customer $model): bool
    {
        return $user->can('customers.delete');
    }
}