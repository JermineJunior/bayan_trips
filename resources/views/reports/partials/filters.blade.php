@props(['action', 'reset' => null, 'singleFilter' => null])

{{-- Shared report filter bar. Each report renders its own single-value filter
     (routes renders none) plus the date range; field and GET-param names
     match the trips index. The quick presets only fill the two date inputs —
     the user still submits with the تصفية button, leaving any other selected
     filter undisturbed. --}}
@php
    $keys = $singleFilter
        ? [$singleFilter['name'], 'date_from', 'date_to']
        : ['date_from', 'date_to'];
    $hasFilters = collect(request()->only($keys))->contains(fn ($value) => $value !== null && $value !== '');

    $today = now()->startOfDay();
    $presets = [
        'اليوم' => [$today->copy(), $today->copy()],
        'أمس' => [$today->copy()->subDay(), $today->copy()->subDay()],
        'آخر 30 يوم' => [$today->copy()->subDays(29), $today->copy()],
        'هذا الشهر' => [$today->copy()->startOfMonth(), $today->copy()],
        'هذا العام' => [$today->copy()->startOfYear(), $today->copy()],
    ];
@endphp

<form method="GET" action="{{ $action }}" x-data="quickDates()">
    <div class="card">
        <div class="flex flex-wrap items-center gap-2 border-b border-border p-3">
            <span class="text-xs font-medium text-muted-foreground">فترات سريعة:</span>

            @foreach ($presets as $label => [$from, $to])
                @php
                    $fromParam = $from->format('Y-m-d');
                    $toParam = $to->format('Y-m-d');
                @endphp
                <button
                    type="button"
                    @click="apply('{{ $fromParam }}', '{{ $toParam }}')"
                    :class="{
                        'btn-secondary': selected === '{{ $fromParam }}|{{ $toParam }}',
                        'btn-ghost': selected !== '{{ $fromParam }}|{{ $toParam }}'
                    }"
                    class="btn btn-sm"
                    :aria-pressed="selected === '{{ $fromParam }}|{{ $toParam }}' ? 'true' : 'false'"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

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
                    x-ref="from"
                    @input="selected = null"
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
                    x-ref="to"
                    @input="selected = null"
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