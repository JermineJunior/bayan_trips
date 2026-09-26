@extends('layouts.app')

@section('title', 'تفاصيل الرحلة')

@section('content')
    <div class="content-container">
        <x-page-header
            title="تفاصيل الرحلة"
            description="العرض الكامل لرحلة واحدة بكافة حساباتها."
            back="{{ route('admin.trips.index') }}"
            back-label="الرحلات"
        >
            <x-slot:actions>
                <a href="{{ $printUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                    <x-icon name="printer" class="size-4" />
                    طباعة التقرير
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="mt-8">
            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">رحلة رقم {{ $trip->id }}</h2>
                    <p class="section-desc">
                        أُنشئت في {{ $generatedAt }}
                    </p>
                </header>

                <div class="panel-body">
                    @include('reports.partials.trip_detail', ['trip' => $trip])
                </div>
            </section>
        </div>
    </div>
@endsection