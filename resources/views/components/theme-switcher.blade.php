@props(['variant' => 'compact'])

<div
    x-data="themeSwitcher"
    data-themes="{{ json_encode($themes) }}"
    data-labels="{{ json_encode($themeLabels) }}"
    data-cookie-name="{{ config('themes.cookie') }}"
    data-default-theme="{{ config('themes.default') }}"
    class="relative"
>
    @if ($variant === 'compact')
        <button
            type="button"
            @click="toggleTheme"
            :aria-label="current === 'dark' ? 'التبديل إلى المظهر الفاتح' : 'التبديل إلى المظهر الداكن'"
            class="btn btn-ghost btn-icon"
        >
            <x-icon name="moon" x-show="current === 'dark'" x-cloak class="size-4" />
            <x-icon name="sun" x-show="current !== 'dark'" x-cloak class="size-4" />
        </button>
    @else
        <button
            type="button"
            @click="toggle"
            :aria-expanded="open"
            aria-haspopup="menu"
            class="btn btn-secondary justify-between"
        >
            <span class="flex items-center gap-2">
                <x-icon name="moon" x-show="current === 'dark'" x-cloak class="size-4 text-primary" />
                <x-icon name="sun" x-show="current !== 'dark'" x-cloak class="size-4 text-primary" />
                <span x-text="labels[current]">المظهر</span>
            </span>
            <x-icon name="chevron-down" class="size-4 text-muted-foreground" />
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.outside="open = false"
            @keydown.escape.window="open = false"
            role="menu"
            class="absolute end-0 z-50 mt-1.5 w-44 rounded-lg border border-border bg-surface p-1 shadow-lg"
        >
            <template x-for="theme in themes" :key="theme">
                <button
                    type="button"
                    role="menuitem"
                    @click="setTheme(theme)"
                    class="menu-item"
                    :class="{ 'bg-muted font-medium text-foreground': current === theme, 'text-muted-foreground': current !== theme }"
                >
                    <span x-text="labels[theme]"></span>
                    <x-icon name="check" class="ms-auto size-4" x-show="current === theme" x-cloak />
                </button>
            </template>
        </div>
    @endif
</div>