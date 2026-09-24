@props([
    'type' => 'success',
    'title' => null,
    'dismissible' => false,
])

@php
    $icons = [
        'success' => 'check',
        'danger' => 'alert-triangle',
        'warning' => 'alert-triangle',
        'info' => 'info',
    ];

    $tones = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
@endphp

<div x-data="{ show: true }" x-show="show" x-cloak role="status" class="alert {{ $tones[$type] ?? 'alert-success' }}">
    <x-icon :name="$icons[$type] ?? 'info'" class="size-5 text-current" />

    <div class="flex-1">
        @if ($title)
            <p class="alert-title">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5' : '' }}">{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            @click="show = false"
            class="shrink-0 rounded-md p-0.5 opacity-70 transition-opacity hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current"
            aria-label="إغلاق الإشعار"
        >
            <x-icon name="x" class="size-4" />
        </button>
    @endif
</div>