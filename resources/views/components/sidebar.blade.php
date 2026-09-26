<div x-data="sidebar">
    {{-- Mobile backdrop --}}
    <div
        x-show="$store.ui.sidebarOpen"
        x-cloak
        x-transition.opacity
        @mousedown="$store.ui.sidebarOpen = false"
        @touchstart.passive="$store.ui.sidebarOpen = false"
        class="fixed inset-0 z-30 bg-foreground/20 backdrop-blur-[1px] lg:hidden"
        aria-hidden="true"
    ></div>

    <aside
        aria-label="القائمة الجانبية"
        class="sidebar-drawer fixed inset-y-0 start-0 z-40 flex h-dvh shrink-0 flex-col border-e border-border bg-surface lg:sticky lg:top-0 lg:h-screen"
        :class="($store.ui.sidebarOpen ? 'open' : '') + (collapsed ? ' collapsed' : '')"
    >
        {{-- Brand --}}
        <a
            href="{{ route('home') }}"
            class="flex h-16 shrink-0 items-center gap-3 border-b border-border px-4"
            :class="mobileOpen || !collapsed ? 'justify-start' : 'justify-center'"
        >
            @if ($logoUrl)
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $appName }}"
                    class="size-8 shrink-0 rounded-md border border-border object-contain bg-background p-0.5"
                >
            @else
                <span
                    class="flex size-8 shrink-0 items-center justify-center rounded-md bg-primary text-sm font-bold text-primary-foreground"
                >
                    {{ mb_substr($appName, 0, 1) }}
                </span>
            @endif
            <span
                x-show="mobileOpen || !collapsed"
                class="sidebar-label truncate text-[0.95rem] font-semibold text-foreground"
            >
                {{ $appName }}
            </span>
        </a>

        {{-- Navigation: each item declares the permission it requires and is
             removed from the DOM entirely when the user lacks it. --}}
        <nav @click="$store.ui.sidebarOpen = false;" class="flex-1 space-y-1 overflow-y-auto p-3">
            <x-sidebar-link
                href="{{ route('home') }}"
                :active="request()->routeIs('home')"
                label="لوحة التحكم"
                icon="dashboard"
            />

            @can('trips.view')
                <x-sidebar-link
                    href="{{ route('admin.trips.index') }}"
                    :active="request()->routeIs('admin.trips.*')"
                    label="الرحلات"
                    icon="truck"
                />
            @endcan

            {{-- Master data section: shown when the user can view at least one
                 of the four master-data screens. --}}
            @canany(['vehicles.view', 'drivers.view', 'customers.view', 'trip_types.view'])
                <p
                    x-show="mobileOpen || !collapsed"
                    class="sidebar-label px-3 pb-1 pt-3 text-xs font-semibold text-muted-foreground/70"
                >
                    البيانات الأساسية
                </p>
            @endcanany

            @can('vehicles.view')
                <x-sidebar-link
                    href="{{ route('admin.vehicles.index') }}"
                    :active="request()->routeIs('admin.vehicles.*')"
                    label="المركبات"
                    icon="car"
                />
            @endcan

            @can('drivers.view')
                <x-sidebar-link
                    href="{{ route('admin.drivers.index') }}"
                    :active="request()->routeIs('admin.drivers.*')"
                    label="السائقون"
                    icon="id-card"
                />
            @endcan

            @can('customers.view')
                <x-sidebar-link
                    href="{{ route('admin.customers.index') }}"
                    :active="request()->routeIs('admin.customers.*')"
                    label="العملاء"
                    icon="contact"
                />
            @endcan

            @can('trip_types.view')
                <x-sidebar-link
                    href="{{ route('admin.trip-types.index') }}"
                    :active="request()->routeIs('admin.trip-types.*')"
                    label="أنواع الرحلات"
                    icon="route"
                />
            @endcan

            {{-- Reports section: every report shares the single reports.view
                 permission. The section is a collapsible accordion; its state
                 is persisted, and it stays expanded in narrow mode so the
                 report icons remain reachable. --}}
            @can('reports.view')
                <button
                    type="button"
                    @click="toggleReports"
                    x-show="mobileOpen || !collapsed"
                    :aria-expanded="reportsOpen ? 'true' : 'false'"
                    aria-controls="sidebar-reports"
                    class="flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-muted-foreground/70 transition-colors hover:bg-muted hover:text-foreground"
                >
                    <span>التقارير</span>
                    <svg
                        :class="reportsOpen ? 'rotate-180' : ''"
                        class="size-4 shrink-0 transition-transform"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>

                <div
                    id="sidebar-reports"
                    x-show="reportsOpen || (collapsed && !mobileOpen)"
                    class="space-y-1"
                >
                    <x-sidebar-link
                        href="{{ route('reports.vehicles.form') }}"
                        :active="request()->routeIs('reports.vehicles.*')"
                        label="المركبات"
                        icon="car"
                    />

                    <x-sidebar-link
                        href="{{ route('reports.drivers.form') }}"
                        :active="request()->routeIs('reports.drivers.*')"
                        label="السائقون"
                        icon="id-card"
                    />

                    <x-sidebar-link
                        href="{{ route('reports.customers.form') }}"
                        :active="request()->routeIs('reports.customers.*')"
                        label="العملاء"
                        icon="contact"
                    />

                    <x-sidebar-link
                        href="{{ route('reports.trip-types.form') }}"
                        :active="request()->routeIs('reports.trip-types.*')"
                        label="أنواع الرحلات"
                        icon="route"
                    />

                    <x-sidebar-link
                        href="{{ route('reports.routes.form') }}"
                        :active="request()->routeIs('reports.routes.*')"
                        label="المسارات"
                        icon="activity"
                    />
                </div>
            @endcan

            @can('users.view')
                <x-sidebar-link
                    href="{{ route('admin.users.index') }}"
                    :active="request()->routeIs('admin.users.*')"
                    label="المستخدمون"
                    icon="users"
                />
            @endcan

            @can('roles.view')
                <x-sidebar-link
                    href="{{ route('admin.roles.index') }}"
                    :active="request()->routeIs('admin.roles.*')"
                    label="الأدوار"
                    icon="roles"
                />
            @endcan

            @can('settings.edit')
                <x-sidebar-link
                    href="{{ route('admin.settings.edit') }}"
                    :active="request()->routeIs('admin.settings.*')"
                    label="الإعدادات"
                    icon="settings"
                />
            @endcan
        </nav>

        {{-- Collapse toggle (desktop) / brand hint (mobile) --}}
        <div class="border-t border-border p-3">
            <button
                type="button"
                @click="toggle"
                :aria-label="collapsed ? 'توسيع القائمة الجانبية' : 'طيّ القائمة الجانبية'"
                class="hidden w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground lg:flex"
                :class="collapsed ? 'justify-center' : 'justify-between'"
            >
                <svg
                    :class="collapsed ? 'size-5 rotate-180' : 'size-5'"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="m11 17-5-5 5-5"></path>
                    <path d="m18 17-5-5 5-5"></path>
                </svg>
                <span x-show="!collapsed" class="sidebar-label text-xs">طيّ القائمة</span>
            </button>

            <p class="px-3 py-2 text-center text-[0.6875rem] leading-4 text-muted-foreground lg:hidden">
                {{ $appName }} · {{ date('Y') }}
            </p>
        </div>
    </aside>
</div>