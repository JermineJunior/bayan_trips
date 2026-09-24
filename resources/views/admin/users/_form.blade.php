@php
    $isEdit = filled($user);
    $selectedRoleId = old('role_id', $user?->roles->first()?->id);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}"
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
            <h2 class="section-title">المعلومات الأساسية</h2>
            <p class="section-desc">البيانات الشخصية للمستخدم وعنوان التواصل.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">الاسم</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user?->name) }}"
                        required
                        autofocus
                        class="input"
                    >
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="label">اسم المستخدم</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username', $user?->username) }}"
                        required
                        dir="ltr"
                        class="input"
                    >
                    @error('username')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="label">
                        البريد الإلكتروني <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user?->email) }}"
                        dir="ltr"
                        class="input"
                    >
                    <p class="hint">يُستخدم حاليًا للتوثيق فقط، وليس لتسجيل الدخول.</p>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">الحساب والأمان</h2>
            <p class="section-desc">الدور وكلمة المرور (عند الإنشاء).</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="role_id" class="label">الدور</label>
                    <select id="role_id" name="role_id" required class="select">
                        <option value="" disabled @selected($selectedRoleId === null)>اختر دورًا…</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected((int) $selectedRoleId === $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                @unless ($isEdit)
                    <div>
                        <label for="password" class="label">
                            كلمة المرور <span class="text-muted-foreground">(اختياري)</span>
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            minlength="8"
                            autocomplete="new-password"
                            dir="ltr"
                            class="input"
                        >
                        <p class="hint">اتركها فارغة لتوليد كلمة مرور عشوائية تظهر لك مرة واحدة بعد الحفظ.</p>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endunless
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إنشاء المستخدم' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>