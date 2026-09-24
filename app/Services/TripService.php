<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripExpense;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TripService
{
    /**
     * Create or update a trip together with its expense lines, atomically,
     * then recalculate the derived amounts once.
     */
    public function save(array $data, array $expenses = [], ?Trip $trip = null): Trip
    {
        return DB::transaction(function () use ($data, $expenses, $trip) {
            if ($trip) {
                $trip->update($data);
            } else {
                $trip = Trip::create($data + ['created_by' => auth('web')->id()]);
            }

            $rows = collect($expenses)
                ->map(fn ($e) => Arr::only($e, ['amount', 'description']))
                ->values()
                ->all();

            // withoutEvents: avoid one recalculation per line; we recalculate once below.
            TripExpense::withoutEvents(function () use ($trip, $rows) {
                $trip->expenses()->delete();
                if ($rows) {
                    $trip->expenses()->createMany($rows);
                }
            });

            $trip->recalculate();

            return $trip;
        });
    }
}