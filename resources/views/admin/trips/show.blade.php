@extends('layouts.app')

@section('title', 'تفاصيل الرحلة')

@section('content')
    <div class="content-container">
        <x-page-header
            title="تفاصيل الرحلة"
            :description="$trip->trip_date->translatedFormat('j F Y') . ' — ' . $trip->from_location . ' ← ' . $trip->to_location"
            back="{{ route('admin.trips.index') }}"
            back-label="الرحلات"
        >
            @can('update', $trip)
                <x-slot:actions>
                    <a href="{{ route('admin.trips.edit', $trip) }}" class="btn btn-primary">
                        <x-icon name="pencil" class="size-4" />
                        تعديل الرحلة
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <section class="card lg:col-span-2">
                <header class="panel-head">
                    <h2 class="section-title">بيانات الرحلة</h2>
                    <p class="section-desc">المعلومات الأساسية للرحلة.</p>
                </header>

                <div class="panel-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="label">تاريخ الرحلة</dt>
                            <dd class="mt-1 text-sm text-foreground" dir="ltr">
                                {{ $trip->trip_date->format('Y-m-d') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="label">نوع الرحلة</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $trip->tripType->name }}</dd>
                        </div>

                        <div>
                            <dt class="label">العميل</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $trip->customer?->name ?: '—' }}</dd>
                        </div>

                        <div>
                            <dt class="label">المركبة</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $trip->vehicle->label }}</dd>
                        </div>

                        <div>
                            <dt class="label">السائق</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $trip->driver->name }}</dd>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="label">من المكان</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ $trip->from_location }}</dd>
                            </div>

                            <div>
                                <dt class="label">إلى المكان</dt>
                                <dd class="mt-1 text-sm text-foreground">{{ $trip->to_location }}</dd>
                            </div>
                        </div>

                        <div>
                            <dt class="label">الملاحظات</dt>
                            <dd class="mt-1 text-sm leading-6 text-foreground">{{ $trip->notes ?: '—' }}</dd>
                        </div>

                        <div>
                            <dt class="label">تاريخ الإنشاء</dt>
                            <dd class="mt-1 text-sm text-foreground">
                                {{ $trip->created_at?->translatedFormat('j F Y') ?: '—' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">تفاصيل الحساب</h2>
                    <p class="section-desc">السعر والمصروفات والحصص.</p>
                </header>

                <div class="panel-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="label">سعر الرحلة</dt>
                            <dd dir="ltr" class="mt-1 text-sm font-medium tabular-nums text-foreground">
                                {{ format_number($trip->price) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="label">إجمالي المصروفات</dt>
                            <dd dir="ltr" class="mt-1 text-sm font-medium tabular-nums text-foreground">
                                {{ format_number($trip->total_expenses) }}
                            </dd>
                        </div>

                        <div class="border-t border-border pt-4">
                            <dt class="label">الصافي</dt>
                            <dd dir="ltr" class="mt-1 text-sm font-semibold tabular-nums text-foreground">
                                {{ format_number($trip->net_amount) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="label">
                                حصة السائق
                                <span class="text-xs text-muted-foreground">
                                    ({{ format_number($trip->driver_percentage) }}%)
                                </span>
                            </dt>
                            <dd dir="ltr" class="mt-1 text-sm font-medium tabular-nums text-foreground">
                                {{ format_number($trip->driver_amount) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="label">حصة الشركة</dt>
                            <dd dir="ltr" class="mt-1 text-sm font-medium tabular-nums text-foreground">
                                {{ format_number($trip->company_amount) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>
        </div>

        <section class="card mt-5">
            <header class="panel-head">
                <h2 class="section-title">المصروفات</h2>
                <p class="section-desc">بند لكل مصروف مسجل على الرحلة.</p>
            </header>

            <div class="panel-body">
                @if ($trip->expenses->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-10 text-center">#</th>
                                    <th>الوصف</th>
                                    <th class="text-end">المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trip->expenses as $i => $expense)
                                    <tr>
                                        <td class="text-center text-muted-foreground">{{ $i + 1 }}</td>
                                        <td class="text-foreground">{{ $expense->description }}</td>
                                        <td dir="ltr" class="whitespace-nowrap text-end tabular-nums text-foreground">
                                            {{ format_number($expense->amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-muted-foreground">لا توجد مصروفات مسجلة على هذه الرحلة.</p>
                @endif
            </div>
        </section>

        @can('delete', $trip)
            <div class="mt-5">
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
                        <button type="button" @click="open = true" class="btn btn-danger-ghost btn-sm">
                            <x-icon name="trash" class="size-4" />
                            حذف الرحلة
                        </button>
                    </x-slot:trigger>
                </x-confirm-modal>
            </div>
        @endcan
    </div>
@endsection