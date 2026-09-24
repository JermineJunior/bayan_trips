<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TripTypeRequest;
use App\Models\TripType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TripTypeController extends Controller
{
    /**
     * Display a paginated listing of the trip types.
     */
    public function index(): View
    {
        $this->authorize('viewAny', TripType::class);

        return view('admin.trip-types.index', [
            'tripTypes' => TripType::orderByDesc('is_default')->orderBy('name')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new trip type.
     */
    public function create(): View
    {
        $this->authorize('create', TripType::class);

        return view('admin.trip-types.create', [
            'tripType' => null,
        ]);
    }

    /**
     * Store a newly created trip type.
     */
    public function store(TripTypeRequest $request): RedirectResponse
    {
        $this->authorize('create', TripType::class);

        TripType::create($request->validated());

        return redirect()
            ->route('admin.trip-types.index')
            ->with('status', 'تمت إضافة نوع الرحلة.');
    }

    /**
     * Show the form for editing the given trip type.
     */
    public function edit(TripType $tripType): View
    {
        $this->authorize('update', $tripType);

        return view('admin.trip-types.edit', [
            'tripType' => $tripType,
        ]);
    }

    /**
     * Update the given trip type.
     */
    public function update(TripTypeRequest $request, TripType $tripType): RedirectResponse
    {
        $this->authorize('update', $tripType);

        $tripType->update($request->validated());

        return redirect()
            ->route('admin.trip-types.index')
            ->with('status', 'تم تحديث نوع الرحلة.');
    }

    /**
     * Remove the given trip type.
     *
     * The default trip type is never deleted, and neither is a trip type that
     * already has trips.
     */
    public function destroy(TripType $tripType): RedirectResponse
    {
        $this->authorize('delete', $tripType);

        if ($tripType->is_default) {
            return redirect()
                ->route('admin.trip-types.index')
                ->with('error', 'لا يمكن حذف نوع الرحلة الافتراضي.');
        }

        if ($tripType->trips()->exists()) {
            return redirect()
                ->route('admin.trip-types.index')
                ->with('error', 'لا يمكن حذف نوع رحلة مرتبط برحلات موجودة.');
        }

        $tripType->delete();

        return redirect()
            ->route('admin.trip-types.index')
            ->with('status', 'تم حذف نوع الرحلة.');
    }
}