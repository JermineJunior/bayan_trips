@extends('layouts.print')

@section('title', $meta['title'])

@section('content')
    @include('reports.partials.print_header')

    <section class="mt-6">
        <div class="border-b border-neutral-300 pb-2">
            <h2 class="text-lg font-bold text-neutral-900">{{ $meta['title'] }}</h2>
            <p class="text-sm text-neutral-600">{{ $meta['description'] }}</p>
        </div>

        @if ($rows->isEmpty())
            <p class="py-8 text-center text-sm text-neutral-500">{{ $meta['empty'] }}</p>
        @else
            <div class="mt-4">
                @include('reports.partials.grouped_table', [
                    'rows' => $rows,
                    'totals' => $totals,
                    'showAvgs' => $meta['showAvgs'],
                ])
            </div>
        @endif
    </section>

    @include('reports.partials.print_footer')
@endsection