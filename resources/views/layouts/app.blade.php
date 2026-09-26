<!DOCTYPE html>
<html
    lang="ar"
    dir="rtl"
    data-theme="{{ $theme }}"
    data-font-size="{{ $userFontSize }}"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', $appName)</title>

        <script>
            try {
                document.documentElement.dataset.sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true' ? '1' : '0';
            } catch (e) {
                document.documentElement.dataset.sidebarCollapsed = '0';
            }
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-background text-foreground antialiased">
        <div class="flex min-h-screen">
            <x-sidebar />

            <div class="flex min-w-0 flex-1 flex-col">
                {{-- Top bar --}}
                <header class="sticky top-0 z-20 flex h-14 shrink-0 items-center gap-2 border-b border-border bg-surface/85 px-4 backdrop-blur-sm sm:px-6">
                    <button
                        type="button"
                        x-data
                        @click="$store.ui.sidebarOpen = true"
                        class="btn btn-ghost btn-icon lg:hidden"
                        aria-label="فتح القائمة الجانبية"
                    >
                        <x-icon name="menu" class="size-5" />
                    </button>

                    <div class="ms-auto flex items-center gap-1">
                        <x-theme-switcher variant="compact" />

                        <div x-data="{ open: false }" class="relative">
                            <button
                                type="button"
                                @click="open = !open"
                                :aria-expanded="open ? 'true' : 'false'"
                                aria-haspopup="menu"
                                class="flex items-center gap-2 rounded-lg p-1.5 transition-colors hover:bg-muted focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                            >
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </span>
                                <span class="hidden max-w-32 truncate text-sm font-medium text-foreground sm:block">
                                    {{ auth()->user()->username }}
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
                                class="absolute end-0 z-40 mt-1.5 w-56 rounded-lg border border-border bg-surface p-1 shadow-lg"
                            >
                                <div class="mb-1 border-b border-border px-2.5 py-2.5">
                                    <p class="truncate text-sm font-semibold text-foreground">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-muted-foreground">{{ auth()->user()->username }}</p>
                                </div>

                                <a
                                    href="{{ route('account.preferences.edit') }}"
                                    @click="open = false"
                                    role="menuitem"
                                    class="menu-item"
                                >
                                    <x-icon name="settings" class="size-4 text-muted-foreground" />
                                    التفضيلات
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" role="menuitem" class="menu-item menu-item-danger">
                                        <x-icon name="logout" class="size-4" />
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    @yield('content')
                </main>

                <footer class="border-t border-border bg-surface/60">
                    <div class="px-4 py-4 text-center text-xs text-muted-foreground sm:px-6">
                        جميع الحقوق محفوظة &copy;  لشركة بيان للبرمجيات. {{ date('Y')  }}
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>