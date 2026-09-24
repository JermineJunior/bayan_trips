@php
    $isEdit = filled($vehicle);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}"
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
            <h2 class="section-title">بيانات المركبة</h2>
            <p class="section-desc">معلومات المركبة الأساسية.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="plate_number" class="label">رقم اللوحة</label>
                    <input
                        id="plate_number"
                        name="plate_number"
                        type="text"
                        value="{{ old('plate_number', $vehicle?->plate_number) }}"
                        required
                        autofocus
                        class="input"
                    >
                    @error('plate_number')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="label">
                        النوع <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="type"
                        name="type"
                        type="text"
                        value="{{ old('type', $vehicle?->type) }}"
                        class="input"
                    >
                    <p class="hint">مثال: صالون، هيلوكس، فان</p>
                    @error('type')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="name" class="label">
                        اسم المركبة <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $vehicle?->name) }}"
                        class="input"
                    >
                    <p class="hint">اسم تعريفي ليسهل تمييز المركبة.</p>
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إضافة المركبة' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>