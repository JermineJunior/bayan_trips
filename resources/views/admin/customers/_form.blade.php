@php
    $isEdit = filled($customer);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.customers.update', $customer) : route('admin.customers.store') }}"
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
            <h2 class="section-title">بيانات العميل</h2>
            <p class="section-desc">معلومات العميل الأساسية.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">الاسم</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $customer?->name) }}"
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
                        value="{{ old('phone', $customer?->phone) }}"
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
                        value="{{ old('address', $customer?->address) }}"
                        class="input"
                    >
                    @error('address')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إضافة العميل' }}'">حفظ</span>
        </button>

        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>