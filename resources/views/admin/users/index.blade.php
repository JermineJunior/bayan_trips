@extends('layouts.app')

@section('title', 'المستخدمون')

@section('content')
    <div class="content-container">
        <x-page-header
            title="المستخدمون"
            :description="number_format($users->total()) . ' مستخدم مسجل في النظام'"
        >
            @can('create', App\Models\User::class)
                <x-slot:actions>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <x-icon name="user-plus" class="size-4" />
                        إضافة مستخدم
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @if ($users->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
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
                                        <td class="whitespace-nowrap text-muted-foreground">
                                            {{ $user->created_at->translatedFormat('j F Y') }}
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('update', $user)
                                                    <a
                                                        href="{{ route('admin.users.edit', $user) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل المستخدم"
                                                        aria-label="تعديل {{ $user->name }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                <x-user-status-toggle :user="$user" />

                                                @can('delete', $user)
                                                    <x-confirm-modal
                                                        :action="route('admin.users.destroy', $user)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف المستخدم ' . $user->name"
                                                        :description="'سيتم حذف حساب المستخدم نهائيًا مع كل بياناته. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف المستخدم"
                                                                aria-label="حذف {{ $user->name }}"
                                                            >
                                                                <x-icon name="trash" class="size-4" />
                                                            </button>
                                                        </x-slot:trigger>
                                                    </x-confirm-modal>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($users->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="users"
                        title="لا يوجد مستخدمون بعد"
                        description="سيظهر المستخدمون هنا بمجرد إنشائهم، ويمكنك إضافة أول مستخدم الآن."
                    >
                        @can('create', App\Models\User::class)
                            <x-slot:action>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <x-icon name="user-plus" class="size-4" />
                                    إضافة مستخدم
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection