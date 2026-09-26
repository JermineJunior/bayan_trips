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
            @if ($rows->isNotEmpty())
                @include('reports.partials.totals', ['totals' => $totals])

                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        @include('reports.partials.grouped_table', [
                            'rows' => $rows,
                            'totals' => $totals,
                            'showAvgs' => $meta['showAvgs'],
                        ])
                    </div>
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