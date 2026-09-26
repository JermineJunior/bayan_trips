@extends('layouts.app')

@section('title', $meta['title'])

@section('content')
    <div class="content-container">
        <x-page-header
            :title="$meta['title']"
            :description="$meta['description']"
            :back="$reset"
            back-label="عوامل التصفية"
        >
            <x-slot:actions>
                <a href="{{ $printUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                    <x-icon name="printer" class="size-4" />
                    طباعة التقرير
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="mt-8 space-y-4">
            @include('reports.partials.date_range')

            @if ($trips->isNotEmpty())
                @include('reports.partials.totals', ['totals' => $totals])

                <div class="space-y-4">
                    @foreach ($trips as $trip)
                        <section class="card">
                            <header class="panel-head">
                                <h2 class="section-title">رحلة رقم {{ $trip->id }}</h2>
                                <p class="section-desc">
                                    {{ $trip->trip_date->translatedFormat('l، j F Y') }}
                                </p>
                            </header>

                            <div class="panel-body">
                                @include('reports.partials.trip_detail', ['trip' => $trip])
                            </div>
                        </section>
                    @endforeach
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="search"
                        title="لا توجد بيانات"
                        :description="$meta['empty']"
                    />
                </div>
            @endif
        </div>
    </div>
@endsection