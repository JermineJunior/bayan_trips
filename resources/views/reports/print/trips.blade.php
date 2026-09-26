@extends('layouts.print')

@section('title', $meta['title'])

@section('content')
    @include('reports.partials.print_header')

    <section class="mt-6">
        <div class="border-b border-neutral-300 pb-2">
            <h2 class="text-lg font-bold text-neutral-900">{{ $meta['title'] }}</h2>
            <p class="text-sm text-neutral-600">{{ $meta['description'] }}</p>
        </div>

        <div class="mt-3">
            @include('reports.partials.date_range')
        </div>

        @if ($trips->isEmpty())
            <p class="py-8 text-center text-sm text-neutral-500">{{ $meta['empty'] }}</p>
        @else
            @foreach ($trips as $trip)
                <div class="mt-6 break-inside-avoid page-break-inside-avoid">
                    <h3 class="mb-2 border-b border-neutral-300 pb-1 text-sm font-bold text-neutral-900">
                        رحلة رقم {{ $trip->id }}
                        <span class="font-normal text-neutral-600">
                            — {{ $trip->trip_date->translatedFormat('l، j F Y') }}
                        </span>
                    </h3>

                    @include('reports.partials.trip_detail', ['trip' => $trip])
                </div>
            @endforeach
        @endif
    </section>

    @include('reports.partials.print_footer')
@endsection