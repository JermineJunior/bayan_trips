@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <div>
<h1 class="text-xl font-bold text-foreground sm:text-2xl">
                    سجّل الدخول إلى حسابك
                </h1>
                <p class="mt-1.5 text-sm leading-6 text-muted-foreground">
                    استخدم اسم المستخدم وكلمة المرور للمتابعة.
                </p>
    </div>

    @if ($errors->any())
        <x-alert type="danger" class="mt-6">
            <ul class="list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="username" class="label">اسم المستخدم</label>
            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username') }}"
                required
                autofocus
                autocomplete="username"
                dir="ltr"
                class="input"
            >
        </div>

        <div>
            <label for="password" class="label">كلمة المرور</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                dir="ltr"
                class="input"
            >
        </div>

        <div class="flex items-center">
            <label for="remember" class="flex items-center gap-2 text-sm text-muted-foreground">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="size-4 rounded border-border accent-primary"
                >
                تذكرني
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-full py-2.5">
            تسجيل الدخول
        </button>
    </form>

    <p class="mt-6 text-center text-xs leading-5 text-muted-foreground">
        لا تملك حسابًا؟ تواصل مع مسؤول النظام لإنشاء حساب.
    </p>
@endsection