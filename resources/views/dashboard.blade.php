@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="content-container">
        <x-page-header
            title="أهلًا بعودتك، {{ auth()->user()->name }}"
            :description="'نظرة عامة على النظام — ' . now()->translatedFormat('l، j F Y')"
        />

        <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6 xl:auto-rows-fr">
            {{-- Featured: trips --}}
            @can('viewAny', App\Models\Trip::class)
                <section
                    class="card card-primary flex flex-col justify-between gap-6 overflow-hidden px-6 py-6 md:col-span-2 xl:col-span-2 xl:row-span-2"
                    aria-label="الرحلات"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-primary-foreground/90">
                            <x-icon name="route" class="size-4" />
                            <span>الرحلات</span>
                        </div>
                        <a
                            href="{{ route('admin.trips.index') }}"
                            class="text-sm font-medium text-primary-foreground/90 transition-colors hover:text-primary-foreground"
                        >
                            عرض الكل
                        </a>
                    </div>

                    <div>
                        <p dir="ltr" class="text-6xl font-semibold leading-none tabular-nums">
                            {{ number_format($stats['trips']) }}
                        </p>
                        <p class="mt-2 text-sm text-primary-foreground/80">رحلة مسجلة في النظام</p>
                    </div>

                    <div class="rounded-lg bg-primary-foreground/10 px-4 py-3">
                        <p class="text-xs text-primary-foreground/80">رحلات هذا الشهر</p>
                        <p dir="ltr" class="mt-1 text-2xl font-semibold tabular-nums">
                            {{ number_format($monthTotals->trip_count) }}
                        </p>
                    </div>
                </section>
            @endcan

            {{-- Vehicles --}}
            @can('viewAny', App\Models\Vehicle::class)
                <section
                    class="card flex flex-col justify-between gap-4 px-5 py-5 xl:col-span-2"
                    aria-label="المركبات"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="car" class="size-4" />
                            <span>المركبات</span>
                        </div>
                        <a
                            href="{{ route('admin.vehicles.index') }}"
                            class="text-muted-foreground transition-colors hover:text-foreground"
                            title="عرض الكل"
                            aria-label="عرض كل المركبات"
                        >
                            <x-icon name="chevron-left" class="size-4" />
                        </a>
                    </div>
                    <a
                        href="{{ route('admin.vehicles.index') }}"
                        dir="ltr"
                        class="block text-3xl font-semibold tabular-nums text-foreground transition-colors hover:text-primary"
                    >
                        {{ number_format($stats['vehicles']) }}
                    </a>
                </section>
            @endcan

            {{-- Drivers --}}
            @can('viewAny', App\Models\Driver::class)
                <section
                    class="card flex flex-col justify-between gap-4 px-5 py-5 xl:col-span-1"
                    aria-label="السائقون"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="id-card" class="size-4" />
                            <span>السائقون</span>
                        </div>
                        <a
                            href="{{ route('admin.drivers.index') }}"
                            class="text-muted-foreground transition-colors hover:text-foreground"
                            title="عرض الكل"
                            aria-label="عرض كل السائقين"
                        >
                            <x-icon name="chevron-left" class="size-4" />
                        </a>
                    </div>
                    <a
                        href="{{ route('admin.drivers.index') }}"
                        dir="ltr"
                        class="block text-3xl font-semibold tabular-nums text-foreground transition-colors hover:text-primary"
                    >
                        {{ number_format($stats['drivers']) }}
                    </a>
                </section>
            @endcan

            {{-- Customers --}}
            @can('viewAny', App\Models\Customer::class)
                <section
                    class="card flex flex-col justify-between gap-4 px-5 py-5 xl:col-span-1"
                    aria-label="العملاء"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="contact" class="size-4" />
                            <span>العملاء</span>
                        </div>
                        <a
                            href="{{ route('admin.customers.index') }}"
                            class="text-muted-foreground transition-colors hover:text-foreground"
                            title="عرض الكل"
                            aria-label="عرض كل العملاء"
                        >
                            <x-icon name="chevron-left" class="size-4" />
                        </a>
                    </div>
                    <a
                        href="{{ route('admin.customers.index') }}"
                        dir="ltr"
                        class="block text-3xl font-semibold tabular-nums text-foreground transition-colors hover:text-primary"
                    >
                        {{ number_format($stats['customers']) }}
                    </a>
                </section>
            @endcan

            {{-- This month's totals --}}
            @can('viewAny', App\Models\Trip::class)
                <section class="card overflow-hidden xl:col-span-4" aria-label="إجماليات الشهر الحالي">
                    <div class="flex items-center justify-between border-b border-border px-5 py-4">
                        <div>
                            <h2 class="section-title">إجماليات هذا الشهر</h2>
                            <p class="section-desc">{{ now()->translatedFormat('F Y') }}</p>
                        </div>
                        <a href="{{ route('admin.trips.index') }}" class="link text-sm">
                            عرض الرحلات
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-px bg-border sm:grid-cols-4">
                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">عدد الرحلات</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ number_format($monthTotals->trip_count) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">إجمالي السعر</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($monthTotals->total_price) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">الصافي</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($monthTotals->total_net) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">حصة الشركة</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($monthTotals->total_company) }}
                            </p>
                        </div>
                    </div>
                </section>
            @endcan

            {{-- Recent trips --}}
            <section class="card overflow-hidden xl:col-span-4" aria-label="أحدث الرحلات">
                <div class="flex items-center justify-between border-b border-border px-5 py-4">
                    <div>
                        <h2 class="section-title">أحدث الرحلات</h2>
                        <p class="section-desc">آخر الرحلات المسجلة في النظام</p>
                    </div>

                    @can('viewAny', App\Models\Trip::class)
                        <a href="{{ route('admin.trips.index') }}" class="link text-sm">
                            عرض الكل
                        </a>
                    @endcan
                </div>

                @if ($recentTrips->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>العميل</th>
                                    <th>المركبة</th>
                                    <th>السائق</th>
                                    <th>من ← إلى</th>
                                    <th class="text-end">الصافي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTrips as $trip)
                                    <tr>
                                        <td class="whitespace-nowrap text-muted-foreground" dir="ltr">
                                            {{ $trip->trip_date->format('Y-m-d') }}
                                        </td>
                                        <td class="text-muted-foreground">{{ $trip->customer?->name ?: '—' }}</td>
                                        <td class="text-muted-foreground">{{ $trip->vehicle->label }}</td>
                                        <td class="text-muted-foreground">{{ $trip->driver->name }}</td>
                                        <td>
                                            <p class="font-medium whitespace-nowrap text-foreground">{{ $trip->from_location }}</p>
                                            <p class="whitespace-nowrap text-xs text-muted-foreground">
                                                <x-icon name="chevron-left" class="size-3" />
                                                {{ $trip->to_location }}
                                            </p>
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end font-medium tabular-nums text-foreground">
                                            {{ format_number($trip->net_amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-5 py-10 text-center text-sm text-muted-foreground">
                        لا توجد رحلات بعد.
                        @can('create', App\Models\Trip::class)
                            <a href="{{ route('admin.trips.create') }}" class="link">
                                أضف أول رحلة
                            </a>
                        @endcan
                    </div>
                @endif
            </section>

            {{-- Quick actions --}}
            @if (
                auth()->user()->can('trips.create')
                || auth()->user()->can('vehicles.create')
                || auth()->user()->can('drivers.create')
                || auth()->user()->can('customers.create')
            )
                <section class="card xl:col-span-2" aria-label="إجراءات سريعة">
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="section-title">إجراءات سريعة</h2>
                        <p class="section-desc">اختصارات المهام الشائعة</p>
                    </div>

                    <div class="flex flex-col gap-1 p-3">
                        @can('trips.create')
                            <a href="{{ route('admin.trips.create') }}" class="menu-item rounded-lg">
                                <x-icon name="route" class="size-4 text-muted-foreground" />
                                إضافة رحلة
                            </a>
                        @endcan

                        @can('vehicles.create')
                            <a href="{{ route('admin.vehicles.create') }}" class="menu-item rounded-lg">
                                <x-icon name="car" class="size-4 text-muted-foreground" />
                                إضافة مركبة
                            </a>
                        @endcan

                        @can('drivers.create')
                            <a href="{{ route('admin.drivers.create') }}" class="menu-item rounded-lg">
                                <x-icon name="id-card" class="size-4 text-muted-foreground" />
                                إضافة سائق
                            </a>
                        @endcan

                        @can('customers.create')
                            <a href="{{ route('admin.customers.create') }}" class="menu-item rounded-lg">
                                <x-icon name="contact" class="size-4 text-muted-foreground" />
                                إضافة عميل
                            </a>
                        @endcan
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection