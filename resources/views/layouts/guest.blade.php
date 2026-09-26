<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="{{ $theme }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $appName)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-foreground antialiased">
    <div class="flex min-h-dvh">
        {{-- Form column --}}
        <main class="relative flex flex-1 flex-col items-center justify-center px-4 py-12 sm:px-6">
            <div class="absolute end-4 top-4">
                <x-theme-switcher variant="compact" />
            </div>

            <div class="mb-8 flex items-center gap-3 lg:hidden">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $appName }}"
                        class="size-8 rounded-md border border-border object-contain bg-surface p-0.5">
                @else
                    <span
                        class="flex size-8 items-center justify-center rounded-md bg-primary text-sm font-bold text-primary-foreground">
                        {{ mb_substr($appName, 0, 1) }}
                    </span>
                @endif
                <span class="text-lg font-semibold text-foreground">{{ $appName }}</span>
            </div>

            <div class="w-full max-w-sm">
                @yield('content')
            </div>
        </main>

        {{-- Brand panel (desktop) --}}
        <div
            class="relative hidden w-[44%] shrink-0 flex-col justify-between overflow-hidden border-s border-border bg-surface p-10 lg:flex">
            <div class="pointer-events-none absolute -end-40 -top-40 size-96 rounded-full bg-primary/10"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-48 -start-32 size-80 rounded-full bg-accent/60"
                aria-hidden="true"></div>

            <div class="relative flex items-center gap-3">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $appName }}"
                        class="size-9 rounded-md border border-border object-contain bg-background p-0.5">
                @else
                    <span
                        class="flex size-9 items-center justify-center rounded-md bg-primary text-sm font-bold text-primary-foreground">
                        {{ mb_substr($appName, 0, 1) }}
                    </span>
                @endif
                <span class="text-lg font-semibold text-foreground">{{ $appName }}</span>
            </div>

            <div class="relative">
                <h2 class="max-w-sm text-2xl font-semibold leading-9 text-foreground">
                    نظام إدارة الرحلات
                </h2>
                <p class="mt-3 max-w-sm text-sm leading-7 text-muted-foreground">
                    بوابة الدخول للوصول إلى لوحة التحكم.
                </p>
            </div>

            <p class="relative text-xs text-muted-foreground">
                جميع الحقوق محفوظة &copy; لشركة بيان للبرمجيات. {{ date('Y') }}
            </p>
        </div>
    </div>
</body>

</html>
