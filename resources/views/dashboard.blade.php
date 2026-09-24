@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="content-container">
        <x-page-header
            title="أهلًا بعودتك، {{ auth()->user()->name }}"
            :description="'نظرة عامة على النظام — ' . now()->translatedFormat('l، j F Y')"
        />

        {{-- Key metrics --}}
        <section class="mt-8" aria-label="مؤشرات النظام">
            <div class="card overflow-hidden">
                <div class="grid grid-cols-2 gap-px bg-border lg:grid-cols-4">
                    <div class="bg-surface px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="users" class="size-4" />
                            <span>المستخدمون</span>
                        </div>
                        <p class="mt-2.5 text-2xl font-semibold tabular-nums text-foreground">
                            {{ number_format($stats['users']) }}
                        </p>
                    </div>

                    <div class="bg-surface px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="user-check" class="size-4" />
                            <span>حسابات نشطة</span>
                        </div>
                        <p class="mt-2.5 text-2xl font-semibold tabular-nums text-foreground">
                            {{ number_format($stats['active']) }}
                        </p>
                    </div>

                    <div class="bg-surface px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="user-x" class="size-4" />
                            <span>حسابات معطلة</span>
                        </div>
                        <p class="mt-2.5 text-2xl font-semibold tabular-nums text-foreground">
                            {{ number_format($stats['inactive']) }}
                        </p>
                    </div>

                    <div class="bg-surface px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <x-icon name="roles" class="size-4" />
                            <span>الأدوار</span>
                        </div>
                        <p class="mt-2.5 text-2xl font-semibold tabular-nums text-foreground">
                            {{ number_format($stats['roles']) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            {{-- Recent users --}}
            <section class="card overflow-hidden lg:col-span-2" aria-label="أحدث المستخدمين">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <div>
                        <h2 class="section-title">أحدث المستخدمين</h2>
                        <p class="text-xs text-muted-foreground">المستخدمون المضافون مؤخرًا</p>
                    </div>

                    @can('users.view')
                        <a href="{{ route('admin.users.index') }}" class="link text-sm">
                            عرض الكل
                        </a>
                    @endcan
                </div>

                @if ($recentUsers->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
                                                    {{ mb_substr($user->name, 0, 1) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="truncate font-medium text-foreground">{{ $user->name }}</p>
                                                    <p class="truncate text-xs text-muted-foreground">{{ $user->username }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $user->roles->first()?->name ?? '—' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                                            </span>
                                        </td>
                                        <td class="text-muted-foreground whitespace-nowrap">
                                            {{ $user->created_at->translatedFormat('j F Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-sm text-muted-foreground">
                        لا يوجد مستخدمون بعد.
                    </div>
                @endif
            </section>

            {{-- Quick actions --}}
            @if (auth()->user()->can('users.create') || auth()->user()->can('roles.create') || auth()->user()->can('settings.edit'))
                <section class="card lg:col-span-1" aria-label="إجراءات سريعة">
                    <div class="border-b border-border px-6 py-4">
                        <h2 class="section-title">إجراءات سريعة</h2>
                        <p class="text-xs text-muted-foreground">اختصارات المهام الشائعة</p>
                    </div>

                    <div class="flex flex-col gap-1 p-3">
                        @can('users.create')
                            <a href="{{ route('admin.users.create') }}" class="menu-item rounded-lg">
                                <x-icon name="user-plus" class="size-4 text-muted-foreground" />
                                إضافة مستخدم
                            </a>
                        @endcan

                        @can('roles.create')
                            <a href="{{ route('admin.roles.create') }}" class="menu-item rounded-lg">
                                <x-icon name="roles" class="size-4 text-muted-foreground" />
                                إنشاء دور
                            </a>
                        @endcan

                        @can('settings.edit')
                            <a href="{{ route('admin.settings.edit') }}" class="menu-item rounded-lg">
                                <x-icon name="settings" class="size-4 text-muted-foreground" />
                                إعدادات التطبيق
                            </a>
                        @endcan
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection