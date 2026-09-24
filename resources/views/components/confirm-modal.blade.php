@props([
    'title',
    'description',
    'action',
    'method' => 'POST',
    'confirmLabel' => 'تأكيد',
    'confirmIcon' => 'trash',
    'tone' => 'danger',
    'triggerLabel' => null,
    'triggerClass' => 'btn btn-ghost btn-icon',
])

@php
    $tones = [
        'danger' => 'text-danger bg-danger/10',
        'warning' => 'text-warning bg-warning/10',
        'success' => 'text-success bg-success/10',
        'primary' => 'text-primary bg-primary/10',
    ];

    $submitClasses = [
        'danger' => 'btn btn-danger btn-sm',
        'warning' => 'btn btn-sm bg-warning text-warning-foreground shadow-xs hover:bg-warning/90',
        'success' => 'btn btn-sm bg-success text-success-foreground shadow-xs hover:bg-success/90',
        'primary' => 'btn btn-primary btn-sm',
    ];
@endphp

<div x-data="{ open: false }" class="contents">
    {{ $trigger ?? '' }}
    @if ($triggerLabel)
        <button type="button" @click="open = true" class="{{ $triggerClass }}">
            {{ $triggerLabel }}
        </button>
    @endif

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        role="dialog"
        aria-modal="true"
        class="fixed inset-0 z-50 flex items-center justify-center bg-foreground/25 p-4"
        @click.self="open = false"
        @keydown.escape.window="open = false"
    >
        <div
            class="w-full max-w-sm rounded-xl border border-border bg-surface p-6 shadow-xl"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex items-start gap-3.5">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full {{ $tones[$tone] ?? $tones['danger'] }}">
                    <x-icon :name="$confirmIcon" class="size-5" />
                </span>

                <div class="min-w-0">
                    <h2 class="text-base font-semibold text-foreground">{{ $title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-muted-foreground">{{ $description }}</p>
                </div>
            </div>

            <form method="POST" action="{{ $action }}" class="mt-6 flex items-center justify-end gap-2">
                @csrf
                @method($method)

                <button type="button" @click="open = false" class="btn btn-secondary btn-sm">
                    إلغاء
                </button>

                <button type="submit" class="{{ $submitClasses[$tone] ?? $submitClasses['danger'] }}">
                    <x-icon :name="$confirmIcon" class="size-4" />
                    {{ $confirmLabel }}
                </button>
            </form>
        </div>
    </div>
</div>