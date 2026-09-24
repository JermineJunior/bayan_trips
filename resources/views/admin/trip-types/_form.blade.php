@php
    $isEdit = filled($tripType);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.trip-types.update', $tripType) : route('admin.trip-types.store') }}"
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
            <h2 class="section-title">نوع الرحلة</h2>
            <p class="section-desc">اسم نوع الرحلة الذي ستُصنَّف به الرحلات.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">اسم نوع الرحلة</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $tripType?->name) }}"
                        required
                        autofocus
                        class="input"
                    >
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                @if ($isEdit && $tripType->is_default)
                    <div class="flex items-end sm:pb-2">
                        <span class="badge badge-success">النوع الافتراضي</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إضافة نوع الرحلة' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.trip-types.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>