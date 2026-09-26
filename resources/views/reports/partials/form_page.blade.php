@extends('layouts.app')

@section('title', $meta['title'])

@section('content')
    <div class="content-container">
        <x-page-header :title="$meta['title']" :description="$meta['description']" />

        <div class="mt-8 space-y-4">
            @if ($errors->any())
                <x-alert type="danger">
                    <ul class="list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @include('reports.partials.filters', [
                'action' => $action,
                'reset' => $reset,
                'singleFilter' => $singleFilter ?? null,
            ])

            <p class="text-sm text-muted-foreground">
                اختر عوامل التصفية ثم اضغط «تصفية» لعرض التقرير.
            </p>
        </div>
    </div>
@endsection