@extends('layouts.app')

@section('title', 'الأدوار')

@section('content')
    <div class="content-container">
        <x-page-header
            title="الأدوار"
            :description="'تحكم بصلاحيات المستخدمين عبر الأدوار.'"
        >
            @can('create', Spatie\Permission\Models\Role::class)
                <x-slot:actions>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <x-icon name="plus" class="size-4" />
                        إنشاء دور
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
            @if ($roles->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الدور</th>
                                    <th>الصلاحيات</th>
                                    <th>المستخدمون</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-accent text-accent-foreground">
                                                    <x-icon name="roles" class="size-4" />
                                                </span>
                                                <span class="font-medium text-foreground">{{ $role->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-neutral">{{ $role->permissions_count }} صلاحية</span>
                                        </td>
                                        <td class="text-muted-foreground">
                                            {{ $role->users_count ?? 0 }} مستخدم
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('update', $role)
                                                    <a
                                                        href="{{ route('admin.roles.edit', $role) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل الدور"
                                                        aria-label="تعديل {{ $role->name }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $role)
                                                    @if (! auth()->user()->hasRole($role))
                                                        <x-confirm-modal
                                                            :action="route('admin.roles.destroy', $role)"
                                                            method="DELETE"
                                                            tone="danger"
                                                            confirm-icon="trash"
                                                            :title="'حذف الدور ' . $role->name"
                                                            :description="'سيتم حذف هذا الدور نهائيًا. لن يُحذف المستخدمون المرتبطون به لكنهم سيفقدون صلاحياته.'"
                                                            confirm-label="حذف"
                                                        >
                                                            <x-slot:trigger>
                                                                <button
                                                                    type="button"
                                                                    @click="open = true"
                                                                    class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                    title="حذف الدور"
                                                                    aria-label="حذف {{ $role->name }}"
                                                                >
                                                                    <x-icon name="trash" class="size-4" />
                                                                </button>
                                                            </x-slot:trigger>
                                                        </x-confirm-modal>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="roles"
                        title="لا توجد أدوار بعد"
                        description="ستظهر الأدوار هنا بمجرد إنشائها، ويمكنك إنشاء أول دور لتنظيم الصلاحيات."
                    >
                        @can('create', Spatie\Permission\Models\Role::class)
                            <x-slot:action>
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                    <x-icon name="plus" class="size-4" />
                                    إنشاء دور
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection