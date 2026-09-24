@props(['user'])

@php
    $activating = ! $user->is_active;
    $canToggle = $activating
        ? auth()->user()->can('activate', $user)
        : auth()->user()->can('deactivate', $user);
@endphp

@if ($canToggle)
    @if ($activating)
        <x-confirm-modal
            :action="route('admin.users.activate', $user)"
            method="POST"
            tone="success"
            :title="'تفعيل حساب ' . $user->name"
            description="سيتم استعادة الوصول إلى الحساب فورًا ويمكن لصاحبه تسجيل الدخول مجددًا."
            confirm-icon="user-check"
            confirm-label="تفعيل"
        >
            <x-slot:trigger>
                <button
                    type="button"
                    @click="open = true"
                    class="btn btn-ghost btn-icon text-success hover:bg-success/10 hover:text-success"
                    title="تفعيل الحساب"
                    aria-label="تفعيل {{ $user->name }}"
                >
                    <x-icon name="user-check" class="size-4" />
                </button>
            </x-slot:trigger>
        </x-confirm-modal>
    @else
        <x-confirm-modal
            :action="route('admin.users.deactivate', $user)"
            method="POST"
            tone="warning"
            :title="'تعطيل حساب ' . $user->name"
            description="سيُمنع صاحب الحساب من تسجيل الدخول فورًا دون حذف بياناته. يمكن تفعيل الحساب في أي وقت."
            confirm-icon="user-x"
            confirm-label="تعطيل"
        >
            <x-slot:trigger>
                <button
                    type="button"
                    @click="open = true"
                    class="btn btn-ghost btn-icon text-warning hover:bg-warning/10 hover:text-warning"
                    title="تعطيل الحساب"
                    aria-label="تعطيل {{ $user->name }}"
                >
                    <x-icon name="user-x" class="size-4" />
                </button>
            </x-slot:trigger>
        </x-confirm-modal>
    @endif
@endif