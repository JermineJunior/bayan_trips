@props(['action', 'reset' => null, 'singleFilter' => null])

{{-- Shared report filter bar. Each report renders its own single-value filter
     (routes renders none) plus the date range; field and GET-param names
     match the trips index. --}}
@php
    $keys = $singleFilter
        ? [$singleFilter['name'], 'date_from', 'date_to']
        : ['date_from', 'date_to'];
    $hasFilters = collect(request()->only($keys))->contains(fn ($value) => $value !== null && $value !== '');
@endphp

<form method="GET" action="{{ $action }}">
    <div class="card">
        <div class="flex flex-wrap items-center gap-2 p-3">
            @if ($singleFilter)
                <div>
                    <label for="{{ $singleFilter['name'] }}" class="sr-only">{{ $singleFilter['label'] }}</label>
                    <select
                        id="{{ $singleFilter['name'] }}"
                        name="{{ $singleFilter['name'] }}"
                        class="select w-44"
                        aria-label="{{ $singleFilter['label'] }}"
                    >
                        <option value="">الكل</option>
                        @foreach ($singleFilter['options'] as $id => $text)
                            <option
                                value="{{ $id }}"
                                @selected(request($singleFilter['name']) == $id)
                            >
                                {{ $text }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label for="date_from" class="sr-only">تاريخ من</label>
                <input
                    id="date_from"
                    name="date_from"
                    type="date"
                    value="{{ request('date_from') }}"
                    title="تاريخ من"
                    aria-label="تاريخ من"
                    class="input w-44"
                >
            </div>

            <div>
                <label for="date_to" class="sr-only">تاريخ إلى</label>
                <input
                    id="date_to"
                    name="date_to"
                    type="date"
                    value="{{ request('date_to') }}"
                    title="تاريخ إلى"
                    aria-label="تاريخ إلى"
                    class="input w-44"
                >
            </div>

            <div class="flex items-center gap-2 ps-1">
                <button type="submit" class="btn btn-secondary">
                    <x-icon name="search" class="size-4" />
                    تصفية
                </button>

                @if ($hasFilters)
                    <a href="{{ $reset ?? $action }}" class="link text-sm">
                        إعادة تعيين
                    </a>
                @endif
            </div>
        </div>
    </div>
</form>