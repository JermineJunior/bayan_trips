<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\TripType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Per-report metadata used to render both the on-screen and print views.
     *
     * @return array<string, mixed>
     */
    private const REPORTS = [
        'vehicles' => [
            'title' => 'تقرير المركبات',
            'description' => 'تجميع الرحلات حسب المركبة مع الإجماليات.',
            'name' => 'المركبة',
            'icon' => 'car',
            'column' => 'vehicle_id',
            'group' => 'vehicle',
            'filters' => ['vehicle_id', 'date_from', 'date_to'],
            'print' => 'reports.print.vehicles',
            'showAvgs' => false,
            'empty' => 'لم نعثر على أي رحلة تطابق عوامل التصفية.',
        ],
        'drivers' => [
            'title' => 'تقرير السائقين',
            'description' => 'تجميع الرحلات حسب السائق مع الإجماليات.',
            'name' => 'السائق',
            'icon' => 'id-card',
            'column' => 'driver_id',
            'group' => 'driver',
            'filters' => ['driver_id', 'date_from', 'date_to'],
            'print' => 'reports.print.drivers',
            'showAvgs' => false,
            'empty' => 'لم نعثر على أي رحلة تطابق عوامل التصفية.',
        ],
        'customers' => [
            'title' => 'تقرير العملاء',
            'description' => 'تجميع الرحلات حسب العميل مع الإجماليات.',
            'name' => 'العميل',
            'icon' => 'contact',
            'column' => 'customer_id',
            'group' => 'customer',
            'filters' => ['customer_id', 'date_from', 'date_to'],
            'print' => 'reports.print.customers',
            'showAvgs' => false,
            'empty' => 'لم نعثر على أي رحلة تطابق عوامل التصفية.',
        ],
        'trip-types' => [
            'title' => 'تقرير أنواع الرحلات',
            'description' => 'تجميع الرحلات حسب النوع مع الإجماليات.',
            'name' => 'النوع',
            'icon' => 'route',
            'column' => 'trip_type_id',
            'group' => 'trip-type',
            'filters' => ['trip_type_id', 'date_from', 'date_to'],
            'print' => 'reports.print.trip-types',
            'showAvgs' => false,
            'empty' => 'لم نعثر على أي رحلة تطابق عوامل التصفية.',
        ],
        'routes' => [
            'title' => 'تقرير المسارات',
            'description' => 'تجميع الرحلات حسب المسار (من ← إلى) مع المتوسطات.',
            'name' => 'المسار',
            'icon' => 'activity',
            'group' => 'route',
            'filters' => ['date_from', 'date_to'],
            'print' => 'reports.print.routes',
            'showAvgs' => true,
            'empty' => 'لم نعثر على أي مسار يطابق عوامل التصفية.',
        ],
    ];

    /**
     * Display the vehicle report filter form (no aggregate queries).
     */
    public function vehiclesForm(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->form('vehicles');
    }

    /**
     * Display the vehicle report grouped results and their print view.
     */
    public function vehiclesResult(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->result('vehicles', $request);
    }

    /**
     * Display the driver report filter form (no aggregate queries).
     */
    public function driversForm(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->form('drivers');
    }

    /**
     * Display the driver report grouped results and their print view.
     */
    public function driversResult(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->result('drivers', $request);
    }

    /**
     * Display the customer report filter form (no aggregate queries).
     */
    public function customersForm(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->form('customers');
    }

    /**
     * Display the customer report grouped results and their print view.
     */
    public function customersResult(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->result('customers', $request);
    }

    /**
     * Display the trip-type report filter form (no aggregate queries).
     */
    public function tripTypesForm(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->form('trip-types');
    }

    /**
     * Display the trip-type report grouped results and their print view.
     */
    public function tripTypesResult(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->result('trip-types', $request);
    }

    /**
     * Display the route report filter form (no aggregate queries).
     */
    public function routesForm(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->form('routes');
    }

    /**
     * Display the route report grouped results and their print view.
     */
    public function routesResult(Request $request): View
    {
        $this->authorize('reports.view');

        return $this->result('routes', $request);
    }

    /**
     * Display the full breakdown of a single trip.
     */
    public function trip(Request $request, Trip $trip): View
    {
        $this->authorize('reports.view');

        $trip->load(['tripType', 'customer', 'vehicle', 'driver', 'expenses']);

        return view($request->boolean('print') ? 'reports.print.trip' : 'reports.trip', [
            'trip' => $trip,
            'printUrl' => $request->fullUrlWithQuery(['print' => 1]),
            'generatedAt' => now()->translatedFormat('l، j F Y - H:i'),
            'filterSummary' => [],
        ]);
    }

    /**
     * Shared filter page for the five grouped reports. Each report renders
     * only its own single-value filter (routes renders none) plus the date
     * range — never runs an aggregate query.
     */
    private function form(string $kind): View
    {
        $meta = self::REPORTS[$kind];

        return view('reports.'.$kind.'.form', [
            'meta' => $meta,
            'singleFilter' => $this->singleFilter($kind),
            'action' => route('reports.'.$kind.'.result'),
            'reset' => route('reports.'.$kind.'.form'),
        ]);
    }

    /**
     * Shared pipeline for the five grouped report results. Renders the
     * on-screen or the print view depending on the print=1 query flag; both
     * reuse the same grouped-table partial so they never drift apart.
     */
    private function result(string $kind, Request $request): View
    {
        $meta = self::REPORTS[$kind];
        $filter = $this->validatedFilter($request, $kind);

        $vehicles = Vehicle::orderBy('plate_number')->get();
        $drivers = Driver::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $tripTypes = TripType::orderByDesc('is_default')->orderBy('name')->get();

        return view($request->boolean('print') ? $meta['print'] : 'reports.'.$kind.'.result', [
            'meta' => $meta,
            'rows' => $this->groupRows($kind, $filter, $customers),
            'totals' => $this->totals($filter),
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'customers' => $customers,
            'tripTypes' => $tripTypes,
            'filterSummary' => $this->filterSummary($filter, $vehicles, $drivers, $customers, $tripTypes),
            'generatedAt' => now()->translatedFormat('l، j F Y - H:i'),
            'printUrl' => $request->fullUrlWithQuery(['print' => 1]),
            'reset' => route('reports.'.$kind.'.form'),
        ]);
    }

    /**
     * Validate the incoming filter params. Each report accepts only its own
     * single-value filter (routes accepts none) plus the shared date range,
     * so a report URL keeps the same query string when swapped between
     * reports.
     *
     * @return array<string, mixed>
     */
    private function validatedFilter(Request $request, string $kind): array
    {
        $keys = self::REPORTS[$kind]['filters'];

        $rules = [];
        foreach ($keys as $key) {
            $rules[$key] = in_array($key, ['date_from', 'date_to'], true)
                ? ['nullable', 'date']
                : ['nullable', 'integer'];
        }

        $request->validate($rules);

        return $request->only($keys);
    }

    /**
     * Configuration for the single-value filter each report form offers, or
     * null for the routes report (grouped by its own dimension). Only the
     * report's own lookup list is loaded.
     *
     * @return array{name: string, label: string, options: \Illuminate\Support\Collection}|null
     */
    private function singleFilter(string $kind): ?array
    {
        $key = collect(self::REPORTS[$kind]['filters'])
            ->first(fn ($column): bool => ! in_array($column, ['date_from', 'date_to'], true));

        if ($key === null) {
            return null;
        }

        $label = match ($key) {
            'vehicle_id' => 'المركبة',
            'driver_id' => 'السائق',
            'customer_id' => 'العميل',
            'trip_type_id' => 'نوع الرحلة',
            default => 'التصنيف',
        };

        $options = match ($key) {
            'vehicle_id' => Vehicle::orderBy('plate_number')->get()->mapWithKeys(fn ($row): array => [$row->getKey() => $row->label]),
            'driver_id' => Driver::orderBy('name')->get()->mapWithKeys(fn ($row): array => [$row->getKey() => $row->name]),
            'customer_id' => Customer::orderBy('name')->get()->mapWithKeys(fn ($row): array => [$row->getKey() => $row->name]),
            'trip_type_id' => TripType::orderByDesc('is_default')->orderBy('name')->get()->mapWithKeys(fn ($row): array => [$row->getKey() => $row->name]),
            default => collect(),
        };

        return [
            'name' => $key,
            'label' => $label,
            'options' => $options,
        ];
    }

    /**
     * Grouped rows for the given report kind, each shaped as a display array
     * with a title, an optional link into the trips index, and the money sums.
     */
    private function groupRows(string $kind, array $filter, Collection $customers): Collection
    {
        $meta = self::REPORTS[$kind];
        $base = Trip::query()->filter($filter);

        $rows = $meta['group'] === 'route'
            ? $this->routeRows($base)
            : $base
                ->select($meta['column'])
                ->selectRaw($this->aggregateColumns(false))
                ->groupBy($meta['column'])
                ->orderByDesc('total_net')
                ->get();

        if ($meta['group'] === 'route') {
            return $rows->map(fn ($row): array => [
                'title' => ($row->from_location ?: '—').' ← '.($row->to_location ?: '—'),
                'link' => route('admin.trips.index', $filter + [
                    'from' => $row->from_location,
                    'to' => $row->to_location,
                ]),
            ] + $this->displayColumns($row, true));
        }

        $key = $meta['column'];

        return $rows->map(function ($row) use ($meta, $key, $filter, $customers): array {
            $id = $row->{$key};

            return [
                'title' => $this->groupLabel($meta['group'], $id, $customers),
                'link' => $id === null ? null : route('admin.trips.index', $filter + [$key => $id]),
            ] + $this->displayColumns($row, false);
        });
    }

    /**
     * Rows grouped by from_location + to_location, with the two averages.
     */
    private function routeRows(Builder $base): Collection
    {
        return $base
            ->select('from_location', 'to_location')
            ->selectRaw($this->aggregateColumns(true))
            ->groupBy('from_location', 'to_location')
            ->orderByDesc('trip_count')
            ->get();
    }

    /**
     * Arabic label for a group. Soft-deleted records still resolve by id;
     * trips without a customer fold into a fixed placeholder group.
     */
    private function groupLabel(string $group, mixed $id, Collection $customers): string
    {
        return match ($group) {
            'vehicle' => (string) $id ? Vehicle::withTrashed()->find($id)?->label : '—',
            'driver' => (string) $id ? Driver::withTrashed()->find($id)?->name : '—',
            'customer' => $id === null
                ? 'بدون عميل'
                : ($customers->firstWhere('id', (int) $id)?->name ?? Customer::withTrashed()->find($id)?->name ?? '—'),
            'trip-type' => (string) $id ? TripType::find($id)?->name : '—',
            default => '—',
        };
    }

    /**
     * Numeric columns carried by every grouped row; the route report also
     * carries the two averages.
     *
     * @return array<string, mixed>
     */
    private function displayColumns(object $row, bool $withAvgs): array
    {
        return [
            'trip_count' => $row->trip_count,
            'total_price' => $row->total_price,
            'avg_price' => $withAvgs ? $row->avg_price : null,
            'total_expenses' => $row->total_expenses,
            'total_net' => $row->total_net,
            'avg_net' => $withAvgs ? $row->avg_net : null,
            'total_driver' => $row->total_driver,
            'total_company' => $row->total_company,
        ];
    }

    /**
     * Overall totals for the whole filtered set. Always computed by its own
     * aggregate query, never summed from the grouped rows.
     */
    private function totals(array $filter): object
    {
        return Trip::query()
            ->filter($filter)
            ->selectRaw('COUNT(*) AS trip_count')
            ->selectRaw('COALESCE(SUM(price), 0) AS total_price')
            ->selectRaw('COALESCE(SUM(total_expenses), 0) AS total_expenses')
            ->selectRaw('COALESCE(SUM(net_amount), 0) AS total_net')
            ->selectRaw('COALESCE(SUM(driver_amount), 0) AS total_driver')
            ->selectRaw('COALESCE(SUM(company_amount), 0) AS total_company')
            ->selectRaw('ROUND(AVG(price), 2) AS avg_price')
            ->selectRaw('ROUND(AVG(net_amount), 2) AS avg_net')
            ->first();
    }

    /**
     * Aggregate expressions shared by grouped rows and the totals query.
     */
    private function aggregateColumns(bool $withAvgs): string
    {
        $columns = 'COUNT(*) AS trip_count'
            .', COALESCE(SUM(price), 0) AS total_price'
            .', COALESCE(SUM(total_expenses), 0) AS total_expenses'
            .', COALESCE(SUM(net_amount), 0) AS total_net'
            .', COALESCE(SUM(driver_amount), 0) AS total_driver'
            .', COALESCE(SUM(company_amount), 0) AS total_company';

        if ($withAvgs) {
            $columns .= ', ROUND(AVG(price), 2) AS avg_price, ROUND(AVG(net_amount), 2) AS avg_net';
        }

        return $columns;
    }

    /**
     * Human-readable summary of the active filters, for the print footer.
     *
     * @return array<int, string>
     */
    private function filterSummary(array $filter, Collection $vehicles, Collection $drivers, Collection $customers, Collection $tripTypes): array
    {
        $parts = [];

        foreach ([
            'date_from' => 'من ',
            'date_to' => 'إلى ',
            'vehicle_id' => 'المركبة: ',
            'driver_id' => 'السائق: ',
            'customer_id' => 'العميل: ',
            'trip_type_id' => 'النوع: ',
        ] as $key => $prefix) {
            if (empty($filter[$key])) {
                continue;
            }

            $label = match ($key) {
                'vehicle_id' => $vehicles->firstWhere('id', (int) $filter[$key])?->label,
                'driver_id' => $drivers->firstWhere('id', (int) $filter[$key])?->name,
                'customer_id' => $customers->firstWhere('id', (int) $filter[$key])?->name,
                'trip_type_id' => $tripTypes->firstWhere('id', (int) $filter[$key])?->name,
                default => null,
            };

            $parts[] = $prefix.($label ?? $filter[$key]);
        }

        return $parts;
    }
}