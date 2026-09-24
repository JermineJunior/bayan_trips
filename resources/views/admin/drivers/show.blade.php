@extends('layouts.app')

@section('title', 'ملف السائق')

@section('content')
    <div class="content-container">
        <x-page-header
            :title="'ملف السائق: ' . $driver->name"
            description="عرض تفاصيل السائق الكاملة."
            back="{{ route('admin.drivers.index') }}"
            back-label="السائقون"
        >
            @can('update', $driver)
                <x-slot:actions>
                    <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-primary">
                        <x-icon name="pencil" class="size-4" />
                        تعديل السائق
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">المعلومات الأساسية</h2>
                    <p class="section-desc">البيانات الشخصية للسائق.</p>
                </header>

                <div class="panel-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="label">الاسم</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $driver->name }}</dd>
                        </div>

                        <div>
                            <dt class="label">رقم الهاتف</dt>
                            <dd class="mt-1 text-sm text-foreground" dir="ltr">{{ $driver->phone ?: '—' }}</dd>
                        </div>

                        <div>
                            <dt class="label">العنوان</dt>
                            <dd class="mt-1 text-sm text-foreground">{{ $driver->address ?: '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">الوثائق والنسبة</h2>
                    <p class="section-desc">الرقم الوطني والنسبة الافتراضية.</p>
                </header>

                <div class="panel-body">
                    <dl class="space-y-4">
                        <div>
                            <dt class="label">الرقم الوطني</dt>
                            <dd class="mt-1 text-sm text-foreground" dir="ltr">{{ $driver->national_id ?: '—' }}</dd>
                        </div>

                        <div>
                            <dt class="label">النسبة الافتراضية</dt>
                            <dd class="mt-1 text-sm text-foreground">
                                {{ format_number($driver->default_percentage) }}%
                            </dd>
                        </div>

                        <div>
                            <dt class="label">تاريخ الإنشاء</dt>
                            <dd class="mt-1 text-sm text-foreground">
                                {{ $driver->created_at->translatedFormat('j F Y') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>
        </div>
    </div>
@endsection