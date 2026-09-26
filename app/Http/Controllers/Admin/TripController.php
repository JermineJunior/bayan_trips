<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TripFormRequest;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\TripType;
use App\Models\Vehicle;
use App\Services\TripService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Display a paginated listing of the trips with an aggregate totals bar
     * computed over the whole filtered set (not just the current page).
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Trip::class);

        $filter = $request->only([
            'date_from', 'date_to', 'vehicle_id', 'driver_id',
            'customer_id', 'trip_type_id', 'from', 'to', 'q',
        ]);

        $query = Trip::query()
            ->with(['tripType', 'customer', 'vehicle', 'driver'])
            ->filter($filter)
            ->orderByDesc('trip_date')
            ->orderByDesc('id');

        $trips = $query->paginate(10)->withQueryString();

        $totals = Trip::query()
            ->filter($filter)
            ->aggregateTotals()
            ->first();

        return view('admin.trips.index', [
            'trips' => $trips,
            'totals' => $totals,
            'tripTypes' => TripType::orderByDesc('is_default')->orderBy('name')->get(),
            'vehicles' => Vehicle::orderBy('plate_number')->get(),
            'drivers' => Driver::orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new trip.
     */
    public function create(): View
    {
        $this->authorize('create', Trip::class);

        return view('admin.trips.create', $this->formData());
    }

    /**
     * Store a newly created trip.
     */
    public function store(TripFormRequest $request): RedirectResponse
    {
        $this->authorize('create', Trip::class);

        [$data, $expenses] = $this->splitPayload($request->validated());

        $trip = app(TripService::class)->save($data, $expenses);

        return redirect()
            ->route('admin.trips.show', $trip)
            ->with('status', 'تمت إضافة الرحلة.');
    }

    /**
     * Display the given trip and its expense lines.
     */
    public function show(Trip $trip): View
    {
        $this->authorize('view', $trip);

        return view('admin.trips.show', [
            'trip' => $trip->load('expenses'),
        ]);
    }

    /**
     * Show the form for editing the given trip.
     */
    public function edit(Trip $trip): View
    {
        $this->authorize('update', $trip);

        return view('admin.trips.edit', ['trip' => $trip] + $this->formData($trip));
    }

    /**
     * Update the given trip.
     */
    public function update(TripFormRequest $request, Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        [$data, $expenses] = $this->splitPayload($request->validated());

        app(TripService::class)->save($data, $expenses, $trip);

        return redirect()
            ->route('admin.trips.show', $trip)
            ->with('status', 'تم تحديث الرحلة.');
    }

    /**
     * Remove the given trip.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorize('delete', $trip);

        $trip->delete();

        return redirect()
            ->route('admin.trips.index')
            ->with('status', 'تم حذف الرحلة.');
    }

    /**
     * Separate the validated payload into trip fields and expense rows.
     *
     * @return array{0: array<string, mixed>, 1: array<int, array<string, mixed>>}
     */
    private function splitPayload(array $validated): array
    {
        $expenses = $validated['expenses'] ?? [];
        unset($validated['expenses']);

        return [$validated, $expenses];
    }

    /**
     * Shared data for the create/edit forms: selectable records and the
     * location autocomplete list. On edit, the trip's own records stay
     * selectable even if they have been soft-deleted since.
     *
     * @return array<string, mixed>
     */
    private function formData(?Trip $trip = null): array
    {
        $tripTypes = TripType::orderByDesc('is_default')->orderBy('name')->get();
        $customers = $this->withOwnRecord(Customer::orderBy('name')->get(), $trip?->customer);
        $vehicles = $this->withOwnRecord(Vehicle::orderBy('plate_number')->get(), $trip?->vehicle);
        $drivers = $this->withOwnRecord(Driver::orderBy('name')->get(), $trip?->driver);

        $locations = collect()
            ->concat(Trip::query()->whereNotNull('from_location')->distinct()->orderBy('from_location')->limit(300)->pluck('from_location'))
            ->concat(Trip::query()->whereNotNull('to_location')->distinct()->orderBy('to_location')->limit(300)->pluck('to_location'))
            ->unique()
            ->sort()
            ->take(300);

        return [
            'tripTypes' => $tripTypes,
            'customers' => $customers,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'locations' => $locations,
        ];
    }

    /**
     * Append the trip's own related record to the list when it is missing
     * (e.g. it was soft-deleted after the trip was created).
     */
    private function withOwnRecord(Collection $list, ?Model $record): Collection
    {
        if ($record && ! $list->contains('id', $record->id)) {
            $list->push($record);
        }

        return $list;
    }
}