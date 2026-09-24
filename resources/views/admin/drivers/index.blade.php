@extends('layouts.app')

@section('title', 'السائقون')

@section('content')
    <div class="content-container">
        <x-page-header
            title="السائقون"
            :description="number_format($drivers->total()) . ' سائق مسجل في النظام'"
        >
            @can('create', App\Models\Driver::class)
                <x-slot:actions>
                    <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                        <x-icon name="id-card" class="size-4" />
                        إضافة سائق
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            <form method="GET" action="{{ route('admin.drivers.index') }}" class="mb-4">
                <div class="flex flex-wrap items-center gap-2">
                    <label for="search" class="sr-only">البحث في السائقين</label>
                    <div class="relative min-w-0 flex-1">
                        <x-icon
                            name="search"
                            class="pointer-events-none absolute start-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <input
                            id="search"
                            name="search"
                            type="search"
                            value="{{ request('search') }}"
                            placeholder="ابحث بالاسم أو رقم الهاتف…"
                            class="input ps-9"
                        >
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        بحث
                    </button>
                </div>
            </form>

            @if ($drivers->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>السائق</th>
                                    <th>رقم الهاتف</th>
                                    <th>النسبة الافتراضية</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drivers as $driver)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
                                                    {{ mb_substr($driver->name, 0, 1) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="truncate font-medium text-foreground">{{ $driver->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-muted-foreground">
                                            {{ $driver->phone ?: '—' }}
                                        </td>
                                        <td class="whitespace-nowrap text-muted-foreground">
                                            {{ format_number($driver->default_percentage) }}%
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('view', $driver)
                                                    <a
                                                        href="{{ route('admin.drivers.show', $driver) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="عرض السائق"
                                                        aria-label="عرض {{ $driver->name }}"
                                                    >
                                                        <x-icon name="eye" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('update', $driver)
                                                    <a
                                                        href="{{ route('admin.drivers.edit', $driver) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل السائق"
                                                        aria-label="تعديل {{ $driver->name }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $driver)
                                                    <x-confirm-modal
                                                        :action="route('admin.drivers.destroy', $driver)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف السائق ' . $driver->name"
                                                        :description="'سيتم حذف السائق. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف السائق"
                                                                aria-label="حذف {{ $driver->name }}"
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

                    @if ($drivers->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $drivers->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="id-card"
                        :title="request('search') ? 'لا توجد نتائج مطابقة' : 'لا يوجد سائقون بعد'"
                        :description="request('search')
                            ? 'لم نعثر على أي سائق يطابق البحث، جرّب كلمات مختلفة.'
                            : 'سيظهر السائقون هنا بمجرد إضافتهم، ويمكنك إضافة أول سائق الآن.'"
                    >
                        @can('create', App\Models\Driver::class)
                            <x-slot:action>
                                <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                                    <x-icon name="id-card" class="size-4" />
                                    إضافة سائق
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection