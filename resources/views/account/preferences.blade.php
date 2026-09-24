@extends('layouts.app')

@section('title', 'تفضيلاتي')

@section('content')
    <div class="form-page">
        <x-page-header
            title="تفضيلاتي"
            description="تُطبق هذه التفضيلات على حسابك أنت فقط ولا تؤثر على المستخدمين الآخرين."
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <form
            method="POST"
            action="{{ route('account.preferences.update') }}"
            class="mt-8 space-y-6"
            x-data="{ saving: false }"
            @submit="saving = true"
        >
            @csrf
            @method('PUT')

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">المظهر</h2>
                    <p class="section-desc">اختيار الوضع الفاتح أو الداكن — يُحفظ على هذا الجهاز.</p>
                </header>

                <div class="panel-body">
                    <span class="mb-2 block text-sm font-medium text-foreground">الوضع</span>
                    <div class="max-w-xs">
                        <x-theme-switcher variant="dropdown" />
                    </div>
                </div>
            </section>

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">الخط</h2>
                    <p class="section-desc">مقياس الخط العام في كل شاشات التطبيق.</p>
                </header>

                <div class="panel-body">
                    <span class="mb-1.5 block text-sm font-medium text-foreground">حجم الخط</span>

                    <fieldset>
                        <legend class="sr-only">حجم الخط</legend>
                        <div class="grid grid-cols-3 gap-2 sm:max-w-md">
                            @php
                                $options = [
                                    'small' => ['صغير', '90%'],
                                    'default' => ['افتراضي', '100%'],
                                    'large' => ['كبير', '115%'],
                                ];
                            @endphp

                            @foreach ($options as $value => [$label, $scale])
                                <label class="cursor-pointer rounded-lg border border-border bg-surface px-3 py-3 text-center transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                                    <input
                                        type="radio"
                                        name="font_size"
                                        value="{{ $value }}"
                                        @checked(old('font_size', $fontSize) === $value)
                                        class="sr-only"
                                    >
                                    <span class="block text-sm font-medium text-foreground">{{ $label }}</span>
                                    <span class="mt-0.5 block text-xs text-muted-foreground" dir="ltr">{{ $scale }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    @error('font_size')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="btn btn-primary" :disabled="saving">
                    <x-icon name="check" class="size-4" />
                    <span x-text="saving ? 'جارٍ الحفظ…' : 'حفظ التفضيلات'">حفظ التفضيلات</span>
                </button>

                <a href="{{ route('home') }}" class="btn btn-secondary">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection