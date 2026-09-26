@extends('layouts.print')

@section('title', 'تفاصيل الرحلة')

@section('content')
    @include('reports.partials.print_header')

    <section class="mt-6">
        <div class="border-b border-neutral-300 pb-2">
            <h2 class="text-lg font-bold text-neutral-900">تفاصيل الرحلة رقم {{ $trip->id }}</h2>
            <p class="text-sm text-neutral-600">العرض الكامل لرحلة واحدة بكافة حساباتها.</p>
        </div>

        <div class="mt-4">
            @include('reports.partials.trip_detail', ['trip' => $trip])
        </div>
    </section>

    @include('reports.partials.print_footer')
@endsection