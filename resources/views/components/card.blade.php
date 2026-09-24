@props([
    'title' => null,
    'description' => null,
    'padding' => true,
])

<div class="card">
    @if ($title)
        <div class="border-b border-border px-6 py-5">
            <h2 class="section-title">{{ $title }}</h2>
            @if ($description)
                <p class="section-desc">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'px-6 py-6' : '' }}">{{ $slot }}</div>
</div>