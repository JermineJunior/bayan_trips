@extends('layouts.app')

@section('title', 'أنواع الرحلات')

@section('content')
    <div class="content-container">
        <x-page-header
            title="أنواع الرحلات"
            :description="number_format($tripTypes->total()) . ' نوع رحلة مسجل في النظام'"
        >
            @can('create', App\Models\TripType::class)
                <x-slot:actions>
                    <a href="{{ route('admin.trip-types.create') }}" class="btn btn-primary">
                        <x-icon name="route" class="size-4" />
                        إضافة نوع رحلة
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        @if (session('error'))
            <x-alert type="danger" class="mt-6" :dismissible="true">
                {{ session('error') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @if ($tripTypes->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>اسم نوع الرحلة</th>
                                    <th>الحالة</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tripTypes as $tripType)
                                    <tr>
                                        <td>
                                            <p class="font-medium text-foreground">{{ $tripType->name }}</p>
                                        </td>
                                        <td>
                                            @if ($tripType->is_default)
                                                <span class="badge badge-success">النوع الافتراضي</span>
                                            @else
                                                <span class="badge">عادي</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('update', $tripType)
                                                    <a
                                                        href="{{ route('admin.trip-types.edit', $tripType) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل نوع الرحلة"
                                                        aria-label="تعديل {{ $tripType->name }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $tripType)
                                                    <x-confirm-modal
                                                        :action="route('admin.trip-types.destroy', $tripType)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف نوع الرحلة ' . $tripType->name"
                                                        :description="'سيتم حذف نوع الرحلة. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف نوع الرحلة"
                                                                aria-label="حذف {{ $tripType->name }}"
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

                    @if ($tripTypes->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $tripTypes->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="route"
                        title="لا توجد أنواع رحلات بعد"
                        description="سيظهر أنواع الرحلات هنا بمجرد إضافتها، ويمكنك إضافة أول نوع الآن."
                    >
                        @can('create', App\Models\TripType::class)
                            <x-slot:action>
                                <a href="{{ route('admin.trip-types.create') }}" class="btn btn-primary">
                                    <x-icon name="route" class="size-4" />
                                    إضافة نوع رحلة
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection