@extends('layouts.app')

@section('title', 'تعديل مستخدم')

@section('content')
    <div class="form-page">
        <x-page-header
            :title="'تعديل المستخدم: ' . $user->name"
            :description="'اسم المستخدم: ' . $user->username"
            back="{{ route('admin.users.index') }}"
            back-label="المستخدمون"
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            @include('admin.users._form', [
                'user' => $user,
            ])

            <section class="mt-6 card">
                <header class="panel-head">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="section-title">حالة الحساب</h2>
                            <p class="section-desc">التحكم بإمكانية وصول صاحب الحساب.</p>
                        </div>

                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->is_active ? 'الحساب نشط' : 'الحساب معطل' }}
                        </span>
                    </div>
                </header>

                <div class="panel-body">
                    <p class="text-sm leading-6 text-muted-foreground">
                        عند تعطيل الحساب يُمنع صاحبه من تسجيل الدخول فورًا (تُسجَّل خروجه من
                        الجلسات المفتوحة) دون حذف بياناته. يمكن تفعيله في أي وقت لاستعادة الوصول.
                    </p>

                    <div class="mt-5">
                        @php $activating = ! $user->is_active; @endphp

                        @if ($activating)
                            @can('activate', $user)
                                <x-confirm-modal
                                    :action="route('admin.users.activate', $user)"
                                    method="POST"
                                    tone="success"
                                    :title="'تفعيل حساب ' . $user->name"
                                    description="سيتم استعادة الوصول إلى الحساب فورًا ويمكن لصاحبه تسجيل الدخول مجددًا."
                                    confirm-icon="user-check"
                                    confirm-label="تفعيل الحساب"
                                    trigger-label="تفعيل الحساب"
                                    trigger-class="btn btn-secondary"
                                />
                            @endcan
                        @else
                            @can('deactivate', $user)
                                <x-confirm-modal
                                    :action="route('admin.users.deactivate', $user)"
                                    method="POST"
                                    tone="warning"
                                    :title="'تعطيل حساب ' . $user->name"
                                    description="سيُمنع صاحب الحساب من تسجيل الدخول فورًا دون حذف بياناته. يمكن تفعيل الحساب في أي وقت."
                                    confirm-icon="user-x"
                                    confirm-label="تعطيل الحساب"
                                    trigger-label="تعطيل الحساب"
                                    trigger-class="btn btn-danger-ghost"
                                />
                            @endcan
                        @endif
                    </div>
                </div>
            </section>

            <section class="mt-6 card">
                <header class="panel-head">
                    <h2 class="section-title">إعادة تعيين كلمة المرور</h2>
                    <p class="section-desc">تحديد كلمة مرور جديدة مباشرة لهذا المستخدم.</p>
                </header>

                <div class="panel-body">
                    <p class="text-sm leading-6 text-muted-foreground">
                        تسري كلمة المرور الجديدة فورًا. لا يُرسل أي إشعار عبر البريد الإلكتروني.
                    </p>

                    @error('password')
                        <x-alert type="danger" class="mt-4">{{ $message }}</x-alert>
                    @enderror

                    <form
                        method="POST"
                        action="{{ route('admin.users.reset-password', $user) }}"
                        class="mt-5"
                        x-data="{ saving: false, confirm: false }"
                        @submit="saving = true"
                    >
                        @csrf

                        <div class="flex flex-wrap items-center gap-3">
                            <label for="reset-password" class="sr-only">كلمة المرور الجديدة</label>
                            <input
                                id="reset-password"
                                name="password"
                                type="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="كلمة المرور الجديدة"
                                dir="ltr"
                                class="input min-w-0 flex-1"
                            >
                            <button
                                type="button"
                                @click="confirm = true"
                                class="btn btn-secondary"
                            >
                                <x-icon name="key" class="size-4" />
                                إعادة تعيين
                            </button>
                        </div>

                        <div
                            x-show="confirm"
                            x-cloak
                            x-transition.opacity
                            class="mt-4 rounded-lg border border-warning/25 bg-warning/10 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <x-icon name="alert-triangle" class="mt-0.5 size-5 text-warning" />
                                <div class="text-sm leading-6 text-foreground">
                                    <p class="font-medium text-warning">تأكيد إعادة التعيين</p>
                                    <p class="mt-0.5">
                                        سيتم استبدال كلمة المرور الحالية للمستخدم بهذه الكلمة الجديدة فورًا.
                                        هل أنت متأكد؟
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center gap-2">
                                <button type="submit" class="btn btn-primary btn-sm" :disabled="saving">
                                    <x-icon name="key" class="size-4" />
                                    <span x-text="saving ? 'جارٍ الحفظ…' : 'تأكيد'">تأكيد</span>
                                </button>
                                <button type="button" @click="confirm = false" class="btn btn-secondary btn-sm">
                                    إلغاء
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection