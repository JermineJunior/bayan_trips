@extends('layouts.app')

@section('title', 'إعدادات التطبيق')

@section('content')
    <div class="form-page">
        <x-page-header
            title="إعدادات التطبيق"
            description="تُطبَّق هذه الإعدادات على جميع المستخدمين فور حفظها."
        />

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <form
            method="POST"
            action="{{ route('admin.settings.update') }}"
            enctype="multipart/form-data"
            class="mt-8 space-y-6"
            x-data="{ saving: false }"
            @submit="saving = true"
        >
            @csrf
            @method('PUT')

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">الهوية</h2>
                    <p class="section-desc">اسم التطبيق الظاهر في الشريط الجانبي وشاشة الدخول والتذييل.</p>
                </header>

                <div class="panel-body">
                    <label for="app_name" class="label">اسم التطبيق</label>
                    <input
                        id="app_name"
                        name="app_name"
                        type="text"
                        value="{{ old('app_name', $appName) }}"
                        required
                        class="input max-w-md"
                    >
                    <p class="hint">يظهر في التنقل والتذييل وكل الشاشات.</p>
                    @error('app_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="card">
                <header class="panel-head">
                    <h2 class="section-title">الشعار</h2>
                    <p class="section-desc">صورة بحد أقصى 2 ميجابايت، تُعرض في الشريط الجانبي وشاشة الدخول.</p>
                </header>

                <div class="panel-body">
                    @if ($logoUrl)
                        <div class="mb-4 flex items-center gap-4">
                            <img
                                src="{{ $logoUrl }}"
                                alt="الشعار الحالي"
                                class="size-12 rounded-lg border border-border object-contain bg-background p-1"
                            >
                            <div>
                                <p class="text-sm font-medium text-foreground">الشعار الحالي</p>
                                <p class="text-xs text-muted-foreground">ارفع صورة جديدة لاستبداله.</p>
                            </div>
                        </div>
                    @endif

                    <input
                        id="logo"
                        name="logo"
                        type="file"
                        accept="image/*"
                        class="input file:me-3 file:rounded-md file:border-0 file:bg-muted file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-foreground"
                    >
                    @error('logo')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="btn btn-primary" :disabled="saving">
                    <x-icon name="check" class="size-4" />
                    <span x-text="saving ? 'جارٍ الحفظ…' : 'حفظ الإعدادات'">حفظ الإعدادات</span>
                </button>

                <a href="{{ route('home') }}" class="btn btn-secondary">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection