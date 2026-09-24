@php
    $isEdit = filled($driver);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.drivers.update', $driver) : route('admin.drivers.store') }}"
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
            <p class="section-desc">البيانات الشخصية للسائق.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">الاسم</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $driver?->name) }}"
                        required
                        autofocus
                        class="input"
                    >
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="label">
                        رقم الهاتف <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone', $driver?->phone) }}"
                        dir="ltr"
                        class="input"
                    >
                    @error('phone')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="label">
                        العنوان <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="address"
                        name="address"
                        type="text"
                        value="{{ old('address', $driver?->address) }}"
                        class="input"
                    >
                    @error('address')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">الوثائق والنسبة</h2>
            <p class="section-desc">الرقم الوطني والنسبة الافتراضية للسائق.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="national_id" class="label">
                        الرقم الوطني <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="national_id"
                        name="national_id"
                        type="text"
                        value="{{ old('national_id', $driver?->national_id) }}"
                        dir="ltr"
                        class="input"
                    >
                    @error('national_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="default_percentage" class="label">النسبة الافتراضية</label>
                    <div class="relative">
                        <input
                            id="default_percentage"
                            name="default_percentage"
                            type="number"
                            value="{{ old('default_percentage', $driver?->default_percentage ?? '') }}"
                            required
                            step="0.01"
                            min="0"
                            max="100"
                            dir="ltr"
                            class="input"
                        >
                        <span class="pointer-events-none absolute end-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">%</span>
                    </div>
                    <p class="hint">القيمة بين 0 و 100 بحد أقصى منزلتين عشريتين.</p>
                    @error('default_percentage')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إضافة السائق' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>