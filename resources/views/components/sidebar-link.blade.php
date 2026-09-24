@props(['href', 'active' => false, 'label', 'icon'])

@php
    $idle = 'text-muted-foreground hover:bg-muted hover:text-foreground';
    $current = 'bg-accent text-accent-foreground font-semibold';
@endphp

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $active ? $current : $idle }}"
    :class="collapsed && !mobileOpen ? 'justify-center' : 'justify-start'"
>
    <x-icon :name="$icon" class="size-5 shrink-0" />
    <span x-show="mobileOpen || !collapsed" class="sidebar-label truncate">{{ $label }}</span>
</a>