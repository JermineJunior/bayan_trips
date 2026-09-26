<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the business overview: trips, vehicles, drivers and customers.
     */
    public function index(): View
    {
        $stats = [
            'trips' => Trip::count(),
            'vehicles' => Vehicle::count(),
            'drivers' => Driver::count(),
            'customers' => Customer::count(),
        ];

        $monthTotals = Trip::query()
            ->whereDate('trip_date', '>=', now()->startOfMonth())
            ->whereDate('trip_date', '<=', now()->endOfMonth())
            ->selectRaw('COUNT(*) AS trip_count')
            ->selectRaw('COALESCE(SUM(price), 0) AS total_price')
            ->selectRaw('COALESCE(SUM(total_expenses), 0) AS total_expenses')
            ->selectRaw('COALESCE(SUM(net_amount), 0) AS total_net')
            ->selectRaw('COALESCE(SUM(company_amount), 0) AS total_company')
            ->first();

        $recentTrips = Trip::with(['tripType', 'customer', 'vehicle', 'driver'])
            ->orderByDesc('trip_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'monthTotals' => $monthTotals,
            'recentTrips' => $recentTrips,
        ]);
    }
}