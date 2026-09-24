@extends('layouts.app')

@section('title', 'المركبات')

@section('content')
    <div class="content-container">
        <x-page-header
            title="المركبات"
            :description="number_format($vehicles->total()) . ' مركبة مسجلة في النظام'"
        >
            @can('create', App\Models\Vehicle::class)
                <x-slot:actions>
                    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                        <x-icon name="car" class="size-4" />
                        إضافة مركبة
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
            <form method="GET" action="{{ route('admin.vehicles.index') }}" class="mb-4">
                <div class="flex flex-wrap items-center gap-2">
                    <label for="search" class="sr-only">البحث في المركبات</label>
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
                            placeholder="ابحث برقم اللوحة أو النوع أو الاسم…"
                            class="input ps-9"
                        >
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        بحث
                    </button>
                </div>
            </form>

            @if ($vehicles->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>رقم اللوحة</th>
                                    <th>النوع</th>
                                    <th>الاسم</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            <p class="font-medium text-foreground">{{ $vehicle->plate_number }}</p>
                                        </td>
                                        <td class="text-muted-foreground">{{ $vehicle->type ?: '—' }}</td>
                                        <td class="text-muted-foreground">{{ $vehicle->name ?: '—' }}</td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('update', $vehicle)
                                                    <a
                                                        href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل المركبة"
                                                        aria-label="تعديل {{ $vehicle->plate_number }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $vehicle)
                                                    <x-confirm-modal
                                                        :action="route('admin.vehicles.destroy', $vehicle)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف المركبة ' . $vehicle->plate_number"
                                                        :description="'سيتم حذف المركبة. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف المركبة"
                                                                aria-label="حذف {{ $vehicle->plate_number }}"
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

                    @if ($vehicles->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $vehicles->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="car"
                        :title="request('search') ? 'لا توجد نتائج مطابقة' : 'لا توجد مركبات بعد'"
                        :description="request('search')
                            ? 'لم نعثر على أي مركبة تطابق البحث، جرّب كلمات مختلفة.'
                            : 'سيظهر المركبات هنا بمجرد إضافتها، ويمكنك إضافة أول مركبة الآن.'"
                    >
                        @can('create', App\Models\Vehicle::class)
                            <x-slot:action>
                                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                                    <x-icon name="car" class="size-4" />
                                    إضافة مركبة
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection