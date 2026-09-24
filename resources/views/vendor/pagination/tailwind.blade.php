@if ($paginator->hasPages())
    <nav role="navigation" aria-label="التنقل بين الصفحات">
        {{-- Mobile: prev / next only --}}
        <div class="flex items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="btn btn-secondary btn-sm disabled:opacity-50">السابقة</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary btn-sm">السابقة</a>
            @endif

            <span class="text-xs text-muted-foreground">
                صفحة {{ $paginator->currentPage() }} من {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary btn-sm">التالية</a>
            @else
                <span class="btn btn-secondary btn-sm disabled:opacity-50">التالية</span>
            @endif
        </div>

        {{-- Desktop: numbered --}}
        <div class="hidden items-center justify-between gap-4 sm:flex">
            <p class="text-xs text-muted-foreground">
                عرض {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }}
                من {{ $paginator->total() }}
            </p>

            <div class="flex items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span class="flex size-8 items-center justify-center rounded-lg text-muted-foreground" aria-disabled="true">
                        <x-icon name="chevron-right" class="size-4" />
                    </span>
                @else
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        rel="prev"
                        class="btn btn-ghost btn-icon"
                        aria-label="الصفحة السابقة"
                    >
                        <x-icon name="chevron-right" class="size-4" />
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="flex size-8 items-center justify-center text-sm text-muted-foreground">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="flex size-8 items-center justify-center rounded-lg bg-primary text-sm font-medium text-primary-foreground"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    class="btn btn-ghost size-8 p-0 text-sm"
                                    aria-label="الصفحة {{ $page }}"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        rel="next"
                        class="btn btn-ghost btn-icon"
                        aria-label="الصفحة التالية"
                    >
                        <x-icon name="chevron-left" class="size-4" />
                    </a>
                @else
                    <span class="flex size-8 items-center justify-center rounded-lg text-muted-foreground" aria-disabled="true">
                        <x-icon name="chevron-left" class="size-4" />
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif