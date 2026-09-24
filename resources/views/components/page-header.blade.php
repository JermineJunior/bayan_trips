@props([
    'title',
    'description' => null,
    'back' => null,
    'backLabel' => 'العودة',
])

<div>
    @if ($back)
        <a
            href="{{ $back }}"
            class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
        >
            <x-icon name="chevron-right" class="size-4" />
            {{ $backLabel }}
        </a>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="min-w-0">
            <h1 class="page-title">{{ $title }}</h1>
            @if ($description)
                <p class="page-desc">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex shrink-0 items-center gap-2">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>