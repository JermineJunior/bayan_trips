<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DriverRequest;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * Display a paginated listing of the drivers.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Driver::class);

        $drivers = Driver::search($request->query('search'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.drivers.index', [
            'drivers' => $drivers,
        ]);
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        $this->authorize('create', Driver::class);

        return view('admin.drivers.create', [
            'driver' => null,
        ]);
    }

    /**
     * Store a newly created driver.
     */
    public function store(DriverRequest $request): RedirectResponse
    {
        $this->authorize('create', Driver::class);

        Driver::create($request->validated());

        return redirect()
            ->route('admin.drivers.index')
            ->with('status', 'تمت إضافة السائق.');
    }

    /**
     * Display the given driver's full details (incl. hidden national_id).
     */
    public function show(Driver $driver): View
    {
        $this->authorize('view', $driver);

        return view('admin.drivers.show', [
            'driver' => $driver,
        ]);
    }

    /**
     * Show the form for editing the given driver.
     */
    public function edit(Driver $driver): View
    {
        $this->authorize('update', $driver);

        return view('admin.drivers.edit', [
            'driver' => $driver,
        ]);
    }

    /**
     * Update the given driver.
     */
    public function update(DriverRequest $request, Driver $driver): RedirectResponse
    {
        $this->authorize('update', $driver);

        $driver->update($request->validated());

        return redirect()
            ->route('admin.drivers.index')
            ->with('status', 'تم تحديث السائق.');
    }

    /**
     * Remove the given driver.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        $this->authorize('delete', $driver);

        $driver->delete();

        return redirect()
            ->route('admin.drivers.index')
            ->with('status', 'تم حذف السائق.');
    }
}