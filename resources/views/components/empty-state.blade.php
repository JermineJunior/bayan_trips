@props([
    'icon' => 'info',
    'title',
    'description' => null,
])

<div class="flex flex-col items-center justify-center px-6 py-14 text-center">
    <div class="flex size-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
        <x-icon :name="$icon" class="size-5" />
    </div>

    <h3 class="mt-4 text-base font-semibold text-foreground">{{ $title }}</h3>

    @if ($description)
        <p class="mt-1 max-w-sm text-sm leading-6 text-muted-foreground">{{ $description }}</p>
    @endif

    @isset($action)
        <div class="mt-5">
            {{ $action }}
        </div>
    @endisset
</div>