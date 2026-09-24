@extends('layouts.app')

@section('title', 'الرحلات')

@php
    $hasFilters = collect(request()->only([
        'date_from', 'date_to', 'vehicle_id', 'driver_id',
        'customer_id', 'trip_type_id', 'from', 'to', 'q',
    ]))->contains(fn ($v) => $v !== null && $v !== '');
@endphp

@section('content')
    <div class="content-container">
        <x-page-header
            title="الرحلات"
            :description="number_format($trips->total()) . ' رحلة مسجلة في النظام'"
        >
            @can('create', App\Models\Trip::class)
                <x-slot:actions>
                    <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">
                        <x-icon name="route" class="size-4" />
                        إضافة رحلة
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8 space-y-4">
            <form method="GET" action="{{ route('admin.trips.index') }}">
                <div class="card">
                    <div class="flex flex-wrap items-center gap-2 p-3">
                        <label class="relative">
                            <span class="sr-only">بحث عام</span>
                            <input
                                name="q"
                                type="search"
                                value="{{ request('q') }}"
                                placeholder="بحث عام…"
                                aria-label="بحث عام"
                                class="input w-52 pe-10"
                            >
                            <x-icon
                                name="search"
                                class="pointer-events-none absolute end-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                            />
                        </label>

                        <div>
                            <label for="trip_type_id" class="sr-only">نوع الرحلة</label>
                            <select id="trip_type_id" name="trip_type_id" class="select w-44" aria-label="نوع الرحلة">
                                <option value="">نوع الرحلة</option>
                                @foreach ($tripTypes as $tripType)
                                    <option
                                        value="{{ $tripType->id }}"
                                        @selected(request('trip_type_id') == $tripType->id)
                                    >
                                        {{ $tripType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="vehicle_id" class="sr-only">المركبة</label>
                            <select id="vehicle_id" name="vehicle_id" class="select w-44" aria-label="المركبة">
                                <option value="">المركبة</option>
                                @foreach ($vehicles as $vehicle)
                                    <option
                                        value="{{ $vehicle->id }}"
                                        @selected(request('vehicle_id') == $vehicle->id)
                                    >
                                        {{ $vehicle->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="driver_id" class="sr-only">السائق</label>
                            <select id="driver_id" name="driver_id" class="select w-44" aria-label="السائق">
                                <option value="">السائق</option>
                                @foreach ($drivers as $driver)
                                    <option
                                        value="{{ $driver->id }}"
                                        @selected(request('driver_id') == $driver->id)
                                    >
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="customer_id" class="sr-only">العميل</label>
                            <select id="customer_id" name="customer_id" class="select w-44" aria-label="العميل">
                                <option value="">العميل</option>
                                @foreach ($customers as $customer)
                                    <option
                                        value="{{ $customer->id }}"
                                        @selected(request('customer_id') == $customer->id)
                                    >
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="sr-only">تاريخ من</label>
                            <input
                                id="date_from"
                                name="date_from"
                                type="date"
                                value="{{ request('date_from') }}"
                                title="تاريخ من"
                                aria-label="تاريخ من"
                                class="input w-44"
                            >
                        </div>

                        <div>
                            <label for="date_to" class="sr-only">تاريخ إلى</label>
                            <input
                                id="date_to"
                                name="date_to"
                                type="date"
                                value="{{ request('date_to') }}"
                                title="تاريخ إلى"
                                aria-label="تاريخ إلى"
                                class="input w-44"
                            >
                        </div>

                        <div>
                            <label for="from" class="sr-only">من مكان</label>
                            <input
                                id="from"
                                name="from"
                                type="text"
                                value="{{ request('from') }}"
                                placeholder="من مكان"
                                aria-label="من مكان"
                                class="input w-36"
                            >
                        </div>

                        <div>
                            <label for="to" class="sr-only">إلى مكان</label>
                            <input
                                id="to"
                                name="to"
                                type="text"
                                value="{{ request('to') }}"
                                placeholder="إلى مكان"
                                aria-label="إلى مكان"
                                class="input w-36"
                            >
                        </div>

                        <div class="flex items-center gap-2 ps-1">
                            <button type="submit" class="btn btn-secondary">
                                <x-icon name="search" class="size-4" />
                                تصفية
                            </button>

                            @if ($hasFilters)
                                <a href="{{ route('admin.trips.index') }}" class="link text-sm">
                                    إعادة تعيين
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            @if ($trips->isNotEmpty())
                <section class="card overflow-hidden" aria-label="إجماليات الرحلات">
                    <div class="grid grid-cols-2 gap-px bg-border sm:grid-cols-3 xl:grid-cols-6">
                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">عدد الرحلات</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ number_format($totals->trip_count) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">إجمالي السعر</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($totals->total_price) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">إجمالي المصروفات</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($totals->total_expenses) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">صافي الإجمالي</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($totals->total_net) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">حصة السائق</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($totals->total_driver) }}
                            </p>
                        </div>

                        <div class="bg-surface px-5 py-4 sm:px-6">
                            <p class="text-sm text-muted-foreground">حصة الشركة</p>
                            <p dir="ltr" class="mt-1.5 text-xl font-semibold tabular-nums text-foreground">
                                {{ format_number($totals->total_company) }}
                            </p>
                        </div>
                    </div>
                </section>

                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النوع</th>
                                    <th>العميل</th>
                                    <th>المركبة</th>
                                    <th>السائق</th>
                                    <th>من ← إلى</th>
                                    <th class="text-end">السعر</th>
                                    <th class="text-end">المصروفات</th>
                                    <th class="text-end">الصافي</th>
                                    <th class="text-end">حصة السائق</th>
                                    <th class="text-end">حصة الشركة</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trips as $trip)
                                    <tr>
                                        <td class="whitespace-nowrap text-muted-foreground" dir="ltr">
                                            {{ $trip->trip_date->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $trip->tripType->name }}</span>
                                        </td>
                                        <td class="text-muted-foreground">{{ $trip->customer?->name ?: '—' }}</td>
                                        <td class="text-muted-foreground">{{ $trip->vehicle->label }}</td>
                                        <td class="text-muted-foreground">{{ $trip->driver->name }}</td>
                                        <td>
                                            <p class="font-medium text-foreground whitespace-nowrap">{{ $trip->from_location }}</p>
                                            <p class="whitespace-nowrap text-xs text-muted-foreground">
                                                <x-icon name="chevron-left" class="size-3" />
                                                {{ $trip->to_location }}
                                            </p>
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end tabular-nums text-foreground">
                                            {{ format_number($trip->price) }}
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end tabular-nums text-muted-foreground">
                                            {{ format_number($trip->total_expenses) }}
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end font-medium tabular-nums text-foreground">
                                            {{ format_number($trip->net_amount) }}
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end tabular-nums text-muted-foreground">
                                            {{ format_number($trip->driver_amount) }}
                                        </td>
                                        <td dir="ltr" class="whitespace-nowrap text-end tabular-nums text-muted-foreground">
                                            {{ format_number($trip->company_amount) }}
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('view', $trip)
                                                    <a
                                                        href="{{ route('admin.trips.show', $trip) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="عرض الرحلة"
                                                        aria-label="عرض الرحلة {{ $trip->trip_date->format('Y-m-d') }}"
                                                    >
                                                        <x-icon name="eye" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('update', $trip)
                                                    <a
                                                        href="{{ route('admin.trips.edit', $trip) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل الرحلة"
                                                        aria-label="تعديل الرحلة {{ $trip->trip_date->format('Y-m-d') }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $trip)
                                                    <x-confirm-modal
                                                        :action="route('admin.trips.destroy', $trip)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف الرحلة ' . $trip->trip_date->format('Y-m-d')"
                                                        :description="'سيتم حذف الرحلة ومصروفاتها. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف الرحلة"
                                                                aria-label="حذف الرحلة {{ $trip->trip_date->format('Y-m-d') }}"
                                                            >
                                                                <x-icon name="trash" class="size-4" />
                                                            </button>
                                                        </x-slot:trigger>
                                                    </x-confirm-modal>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($trips->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $trips->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="route"
                        :title="$hasFilters ? 'لا توجد نتائج مطابقة' : 'لا توجد رحلات بعد'"
                        :description="$hasFilters
                            ? 'لم نعثر على أي رحلة تطابق عوامل التصفية، جرّب تعديلها أو إعادة التعيين.'
                            : 'سيظهر الرحلات هنا بمجرد إضافتها، ويمكنك إضافة أول رحلة الآن.'"
                    >
                        @can('create', App\Models\Trip::class)
                            <x-slot:action>
                                <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">
                                    <x-icon name="route" class="size-4" />
                                    إضافة رحلة
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection