{{-- Print letterhead: logo + app name, with the short company contact line
     directly beneath. The contact line is omitted when both fields are empty. --}}
<header class="border-b-2 border-neutral-300 pb-4">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if ($logoUrl)
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $appName }}"
                    class="h-12 w-12 rounded-lg object-cover"
                >
            @endif
            <div>
                <h1 class="text-2xl font-extrabold text-neutral-900">{{ $appName }}</h1>
                @if ($companyPhone || $companyLocation)
                    <p class="mt-1 text-sm text-neutral-600">
                        @if ($companyPhone)
                            <span dir="ltr">{{ $companyPhone }}</span>
                            @if ($companyLocation)
                                <span class="mx-1">•</span>
                            @endif
                        @endif
                        @if ($companyLocation)
                            {{ $companyLocation }}
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <p class="text-xs text-neutral-500">{{ $generatedAt }}</p>
    </div>
</header>