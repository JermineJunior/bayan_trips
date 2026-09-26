<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission catalogue
    |--------------------------------------------------------------------------
    |
    | Single source of truth for every permission in the application, grouped
    | by feature area for readability. Each key follows the
    | `<feature>.<action>` convention and is stored verbatim in the
    | `permissions` table (guard: web).
    |
    | The PermissionSeeder reads this file and syncs it into the database:
    | permissions listed here are created if missing, and any permission that
    | exists in the DB but no longer appears here is reported.
    |
    | To add a permission: add its key to the relevant group, then re-run
    |
    |   php artisan db:seed --class=PermissionSeeder
    |   php artisan db:seed --class=RoleSeeder        # re-syncs seeded roles
    |
    */

    'users' => [
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
    ],

    'roles' => [
        'roles.view',
        'roles.create',
        'roles.edit',
        'roles.delete',
    ],

    'settings' => [
        'settings.edit',
    ],

    'vehicles' => [
        'vehicles.view',
        'vehicles.create',
        'vehicles.edit',
        'vehicles.delete',
    ],

    'drivers' => [
        'drivers.view',
        'drivers.create',
        'drivers.edit',
        'drivers.delete',
    ],

    'customers' => [
        'customers.view',
        'customers.create',
        'customers.edit',
        'customers.delete',
    ],

    'trip_types' => [
        'trip_types.view',
        'trip_types.create',
        'trip_types.edit',
        'trip_types.delete',
    ],

    'trips' => [
        'trips.view',
        'trips.create',
        'trips.edit',
        'trips.delete',
    ],

    'reports' => [
        'reports.view',
    ],

];
