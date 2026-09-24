@php
    $isEdit = filled($role);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
    class="space-y-6"
    x-data="{ saving: false }"
    @submit="saving = true"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">معلومات الدور</h2>
            <p class="section-desc">الاسم الظاهر في النظام وفي شاشة المستخدمين.</p>
        </header>

        <div class="panel-body">
            <label for="name" class="label">اسم الدور</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $role?->name) }}"
                required
                autofocus
                class="input max-w-md"
            >
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">الصلاحيات</h2>
            <p class="section-desc">حدد الإجراءات التي يسمح بها هذا الدور في كل قسم.</p>
        </header>

        <div class="panel-body">
            <div class="space-y-5">
                @foreach ($permissionGroups as $area => $permissions)
                    <div
                        class="rounded-lg border border-border"
                        x-data="{
                            allChecked: false,
                            boxes() { return this.$el.querySelectorAll('input[type=checkbox]:not([data-master])'); },
                            toggleAll() {
                                const boxes = this.boxes();
                                this.allChecked = ! this.allChecked;
                                boxes.forEach((box) => { box.checked = this.allChecked; });
                            },
                            refresh() {
                                const boxes = this.boxes();
                                this.allChecked = boxes.length > 0 && [...boxes].every((box) => box.checked);
                            },
                        }"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border px-4 py-3">
                            <h3 class="text-sm font-semibold text-foreground">{{ $area }}</h3>

                            <label class="flex cursor-pointer items-center gap-2 text-xs font-medium text-muted-foreground">
                                <input
                                    type="checkbox"
                                    data-master
                                    @change="toggleAll()"
                                    :checked="allChecked"
                                    class="size-4 rounded border-border accent-primary"
                                >
                                تحديد الكل
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-1.5 p-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($permissions as $permission)
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-md px-2 py-1.5 text-sm text-muted-foreground transition-colors hover:bg-muted">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission }}"
                                        @checked(in_array($permission, old('permissions', $rolePermissions), true))
                                        @change="refresh()"
                                        class="size-4 rounded border-border accent-primary"
                                    >
                                    <code class="text-xs">{{ config('permission-labels.permissions')[$permission] ?? $permission }}</code>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @error('permissions')
                <p class="mt-4 field-error">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إنشاء الدور' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>