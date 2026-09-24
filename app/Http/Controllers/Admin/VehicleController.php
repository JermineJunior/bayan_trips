<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display a paginated listing of the vehicles.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Vehicle::class);

        $vehicles = Vehicle::search($request->query('search'))
            ->orderBy('plate_number')
            ->paginate(10)
            ->withQueryString();

        return view('admin.vehicles.index', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        $this->authorize('create', Vehicle::class);

        return view('admin.vehicles.create', [
            'vehicle' => null,
        ]);
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(VehicleRequest $request): RedirectResponse
    {
        $this->authorize('create', Vehicle::class);

        Vehicle::create($request->validated());

        return redirect()
            ->route('admin.vehicles.index')
            ->with('status', 'تمت إضافة المركبة.');
    }

    /**
     * Show the form for editing the given vehicle.
     */
    public function edit(Vehicle $vehicle): View
    {
        $this->authorize('update', $vehicle);

        return view('admin.vehicles.edit', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the given vehicle.
     */
    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('update', $vehicle);

        $vehicle->update($request->validated());

        return redirect()
            ->route('admin.vehicles.index')
            ->with('status', 'تم تحديث المركبة.');
    }

    /**
     * Remove the given vehicle.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('status', 'تم حذف المركبة.');
    }
}