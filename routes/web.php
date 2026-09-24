<?php

use App\Http\Controllers\Account\PreferencesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\TripTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Read-only pages share the roles.view permission.
    Route::middleware('can:roles.view')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::get('roles/{role}', [RoleController::class, 'edit'])->name('roles.edit');
    });

    Route::post('roles', [RoleController::class, 'store'])
        ->middleware('can:roles.create')
        ->name('roles.store');

    Route::put('roles/{role}', [RoleController::class, 'update'])
        ->middleware('can:roles.edit')
        ->name('roles.update');

    Route::delete('roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('can:roles.delete')
        ->name('roles.destroy');

    // Read-only pages share the users.view permission.
    Route::middleware('can:users.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('users/{user}', [UserController::class, 'edit'])->name('users.edit');
    });

    Route::post('users', [UserController::class, 'store'])
        ->middleware('can:users.create')
        ->name('users.store');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->middleware('can:users.edit')
        ->name('users.update');

    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('can:users.edit')
        ->name('users.reset-password');

    Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])
        ->middleware('can:users.edit')
        ->name('users.deactivate');

    Route::post('users/{user}/activate', [UserController::class, 'activate'])
        ->middleware('can:users.edit')
        ->name('users.activate');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('can:users.delete')
        ->name('users.destroy');

    // The settings screen is a single permission: edit grants access to both
    // viewing the form and saving changes.
    Route::middleware('can:settings.edit')->group(function () {
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Read-only pages share the vehicles.view permission.
    Route::middleware('can:vehicles.view')->group(function () {
        Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::get('vehicles/{vehicle}', [VehicleController::class, 'edit'])->name('vehicles.edit');
    });

    Route::post('vehicles', [VehicleController::class, 'store'])
        ->middleware('can:vehicles.create')
        ->name('vehicles.store');

    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update'])
        ->middleware('can:vehicles.edit')
        ->name('vehicles.update');

    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy'])
        ->middleware('can:vehicles.delete')
        ->name('vehicles.destroy');

    // Read-only pages share the drivers.view permission. The show route is a
    // read-only page so it lives inside the same middleware group.
    Route::middleware('can:drivers.view')->group(function () {
        Route::get('drivers', [DriverController::class, 'index'])->name('drivers.index');
        Route::get('drivers/create', [DriverController::class, 'create'])->name('drivers.create');
        Route::get('drivers/{driver}', [DriverController::class, 'show'])->name('drivers.show');
        Route::get('drivers/{driver}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    });

    Route::post('drivers', [DriverController::class, 'store'])
        ->middleware('can:drivers.create')
        ->name('drivers.store');

    Route::put('drivers/{driver}', [DriverController::class, 'update'])
        ->middleware('can:drivers.edit')
        ->name('drivers.update');

    Route::delete('drivers/{driver}', [DriverController::class, 'destroy'])
        ->middleware('can:drivers.delete')
        ->name('drivers.destroy');

    // Read-only pages share the customers.view permission.
    Route::middleware('can:customers.view')->group(function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::get('customers/{customer}', [CustomerController::class, 'edit'])->name('customers.edit');
    });

    Route::post('customers', [CustomerController::class, 'store'])
        ->middleware('can:customers.create')
        ->name('customers.store');

    Route::put('customers/{customer}', [CustomerController::class, 'update'])
        ->middleware('can:customers.edit')
        ->name('customers.update');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])
        ->middleware('can:customers.delete')
        ->name('customers.destroy');

    // JSON endpoint used by the quick-add customer modal on the trip form.
    Route::post('customers/quick-store', [CustomerController::class, 'quickStore'])
        ->middleware('can:customers.create')
        ->name('customers.quick-store');

    // Read-only pages share the trip_types.view permission.
    Route::middleware('can:trip_types.view')->group(function () {
        Route::get('trip-types', [TripTypeController::class, 'index'])->name('trip-types.index');
        Route::get('trip-types/create', [TripTypeController::class, 'create'])->name('trip-types.create');
        Route::get('trip-types/{tripType}', [TripTypeController::class, 'edit'])->name('trip-types.edit');
    });

    Route::post('trip-types', [TripTypeController::class, 'store'])
        ->middleware('can:trip_types.create')
        ->name('trip-types.store');

    Route::put('trip-types/{tripType}', [TripTypeController::class, 'update'])
        ->middleware('can:trip_types.edit')
        ->name('trip-types.update');

    Route::delete('trip-types/{tripType}', [TripTypeController::class, 'destroy'])
        ->middleware('can:trip_types.delete')
        ->name('trip-types.destroy');

    // Read-only pages share the trips.view permission. The show route is a
    // read-only page so it lives inside the same middleware group.
    Route::middleware('can:trips.view')->group(function () {
        Route::get('trips', [TripController::class, 'index'])->name('trips.index');
        Route::get('trips/create', [TripController::class, 'create'])->name('trips.create');
        Route::get('trips/{trip}', [TripController::class, 'show'])->name('trips.show');
        Route::get('trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    });

    Route::post('trips', [TripController::class, 'store'])
        ->middleware('can:trips.create')
        ->name('trips.store');

    Route::put('trips/{trip}', [TripController::class, 'update'])
        ->middleware('can:trips.edit')
        ->name('trips.update');

    Route::delete('trips/{trip}', [TripController::class, 'destroy'])
        ->middleware('can:trips.delete')
        ->name('trips.destroy');
});

// Account preferences: available to any authenticated user (no permission).
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('preferences', [PreferencesController::class, 'edit'])->name('preferences.edit');
    Route::put('preferences', [PreferencesController::class, 'update'])->name('preferences.update');
});
