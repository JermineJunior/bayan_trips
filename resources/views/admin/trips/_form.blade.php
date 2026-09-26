@php
    $isEdit = filled($trip);
    $defaultTripTypeId = $tripTypes->firstWhere('is_default', true)?->id;

    $initialTripTypeId = old('trip_type_id', $trip?->trip_type_id ?? $defaultTripTypeId ?? '');
    $initialCustomerId = old('customer_id', $trip?->customer_id ?? '');
    $initialVehicleId = old('vehicle_id', $trip?->vehicle_id ?? '');
    $initialDriverId = old('driver_id', $trip?->driver_id ?? '');
    $initialPrice = old('price', $trip?->price ?? '');
    $initialPercentage = old('driver_percentage', $trip?->driver_percentage ?? '');
    $initialExpenses = old('expenses', $trip?->expenses->map(fn ($e) => [
        'amount' => $e->amount,
        'description' => $e->description,
    ])->all() ?? []);
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.trips.update', $trip) : route('admin.trips.store') }}"
    class="space-y-6"
    x-data="{
        saving: false,
        price: {{ Js::from($initialPrice) }},
        driverPercentage: {{ Js::from($initialPercentage) }},
        expenses: {{ Js::from($initialExpenses) }},

        customerModalOpen: false,
        customerSubmitting: false,
        customerForm: { name: '', phone: '', address: '' },
        customerErrors: {},

        get totalExpenses() {
            return this.expenses.reduce((sum, e) => sum + (parseFloat(e.amount) || 0), 0);
        },
        get net() {
            return (parseFloat(this.price) || 0) - this.totalExpenses;
        },
        get driverShare() {
            return this.net * (parseFloat(this.driverPercentage) || 0) / 100;
        },
        get companyShare() {
            return this.net - this.driverShare;
        },
        fmt(value) {
            const n = Math.round((parseFloat(value) || 0) * 100) / 100;
            const [intPart, decPart] = n.toFixed(2).split('.');
            const grouped = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return decPart === '00' ? grouped : grouped + '.' + decPart;
        },
        addExpense() {
            this.expenses.push({ amount: '', description: '' });
        },
        removeExpense(index) {
            this.expenses.splice(index, 1);
        },
        updateDriverPercentage(option) {
            if (!option || option.dataset.defaultPercentage === undefined) return;
            this.driverPercentage = option.dataset.defaultPercentage;
        },
        async quickAddCustomer() {
            this.customerSubmitting = true;
            this.customerErrors = {};

            if (!this.customerForm.name || !this.customerForm.name.trim()) {
                this.customerErrors.name = ['اسم العميل مطلوب.'];
                this.customerSubmitting = false;
                return;
            }

            try {
                const res = await fetch('{{ route('admin.customers.quick-store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify(this.customerForm),
                });

                const data = await res.json().catch(() => null);

                if (!res.ok || !data || typeof data.id !== 'number') {
                    this.customerErrors.form = ['تعذر إضافة العميل الآن، حاول مرة أخرى.'];
                    return;
                }

                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                this.$refs.customerSelect.appendChild(option);
                this.$refs.customerSelect.value = data.id;
                this.customerModalOpen = false;
                this.customerForm = { name: '', phone: '', address: '' };
            } catch {
                this.customerErrors.form = ['تعذر إضافة العميل الآن، حاول مرة أخرى.'];
            } finally {
                this.customerSubmitting = false;
            }
        },
        closeCustomerModal() {
            this.customerModalOpen = false;
            this.customerErrors = {};
        },
    }"
    @submit="saving = true"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <datalist id="trips-locations">
        @foreach ($locations as $location)
            <option value="{{ $location }}"></option>
        @endforeach
    </datalist>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">بيانات الرحلة</h2>
            <p class="section-desc">معلومات أساسية عن وجهة الرحلة ووسائلها.</p>
        </header>

        <div class="panel-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="trip_type_id" class="label">نوع الرحلة</label>
                    <select id="trip_type_id" name="trip_type_id" class="select" required>
                        <option value="">اختر نوع الرحلة</option>
                        @foreach ($tripTypes as $tripType)
                            <option value="{{ $tripType->id }}" @selected($initialTripTypeId == $tripType->id)>
                                {{ $tripType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('trip_type_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="trip_date" class="label">تاريخ الرحلة</label>
                    <input
                        id="trip_date"
                        name="trip_date"
                        type="date"
                        value="{{ old('trip_date', $trip?->trip_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                        required
                        class="input"
                    >
                    @error('trip_date')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_id" class="label">
                        العميل <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <div class="flex gap-2">
                        <div class="min-w-0 flex-1">
                            <select id="customer_id" name="customer_id" x-ref="customerSelect" class="select">
                                <option value="">بدون عميل</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected($initialCustomerId == $customer->id)>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @can('create', App\Models\Customer::class)
                            <button
                                type="button"
                                @click="customerModalOpen = true"
                                class="btn btn-secondary btn-icon shrink-0"
                                title="إضافة عميل جديد"
                                aria-label="إضافة عميل جديد"
                            >
                                <x-icon name="plus" class="size-4" />
                            </button>
                        @endcan
                    </div>
                    @error('customer_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vehicle_id" class="label">المركبة</label>
                    <select id="vehicle_id" name="vehicle_id" class="select" required>
                        <option value="">اختر المركبة</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected($initialVehicleId == $vehicle->id)>
                                {{ $vehicle->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="driver_id" class="label">السائق</label>
                    <select
                        id="driver_id"
                        name="driver_id"
                        class="select"
                        required
                        @change="updateDriverPercentage($event.target.selectedOptions[0])"
                    >
                        <option value="">اختر السائق</option>
                        @foreach ($drivers as $driver)
                            <option
                                value="{{ $driver->id }}"
                                data-default-percentage="{{ format_number($driver->default_percentage) }}"
                                @selected($initialDriverId == $driver->id)
                            >
                                {{ $driver->name }} — {{ format_number($driver->default_percentage) }}%
                            </option>
                        @endforeach
                    </select>
                    <p class="hint">تُعبأ النسبة تلقائيًا عند اختيار السائق.</p>
                    @error('driver_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="driver_percentage" class="label">نسبة السائق</label>
                    <div class="relative">
                        <input
                            id="driver_percentage"
                            name="driver_percentage"
                            type="number"
                            x-model.number="driverPercentage"
                            step="any"
                            min="0"
                            max="100"
                            required
                            dir="ltr"
                            class="input input-suffix [appearance:textfield]"
                        >
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">%</span>
                    </div>
                    <p class="hint">القيمة بين 0 و 100 بحد أقصى منزلتين عشريتين.</p>
                    @error('driver_percentage')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="from_location" class="label">من المكان</label>
                    <input
                        id="from_location"
                        name="from_location"
                        type="text"
                        list="trips-locations"
                        value="{{ old('from_location', $trip?->from_location) }}"
                        placeholder="مثال: بحري"
                        required
                        class="input"
                    >
                    @error('from_location')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="to_location" class="label">إلى المكان</label>
                    <input
                        id="to_location"
                        name="to_location"
                        type="text"
                        list="trips-locations"
                        value="{{ old('to_location', $trip?->to_location) }}"
                        placeholder="مثال: الخرطوم"
                        required
                        class="input"
                    >
                    @error('to_location')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="price" class="label">سعر الرحلة</label>
                    <input
                        id="price"
                        name="price"
                        type="number"
                        x-model.number="price"
                        step="any"
                        min="0"
                        required
                        dir="ltr"
                        placeholder="0.00"
                        class="input"
                    >
                    <p class="hint">يُستخدم لحساب الصافي والحصص بعد خصم المصروفات.</p>
                    @error('price')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="label">
                        ملاحظات <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="textarea"
                    >{{ old('notes', $trip?->notes) }}</textarea>
                    @error('notes')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">المصروفات</h2>
            <p class="section-desc">مصاريف الرحلة، يمكن ترك القائمة فارغة.</p>
        </header>

        <div class="panel-body">
            <div class="space-y-3">
                <template x-for="(expense, index) in expenses" :key="index">
                    <div class="flex flex-col gap-3 rounded-lg border border-border p-3 sm:flex-row sm:items-end">
                        <div class="w-full sm:w-36">
                            <label :for="'expense_amount_' + index" class="label">المبلغ</label>
                            <input
                                :id="'expense_amount_' + index"
                                :name="'expenses[' + index + '][amount]'"
                                type="number"
                                step="0.01"
                                min="0"
                                dir="ltr"
                                x-model="expense.amount"
                                class="input"
                            >
                        </div>
                        <div class="min-w-0 flex-1">
                            <label :for="'expense_desc_' + index" class="label">الوصف</label>
                            <input
                                :id="'expense_desc_' + index"
                                :name="'expenses[' + index + '][description]'"
                                type="text"
                                x-model="expense.description"
                                placeholder="مثال: وقود، رسوم طرق…"
                                class="input"
                            >
                        </div>
                        <button
                            type="button"
                            @click="removeExpense(index)"
                            class="btn btn-ghost btn-icon shrink-0 text-danger hover:bg-danger/10 hover:text-danger"
                            title="حذف المصروف"
                            :aria-label="'حذف المصروف ' + (index + 1)"
                        >
                            <x-icon name="trash" class="size-4" />
                        </button>
                    </div>
                </template>
            </div>

            @error('expenses.*')
                <p class="mt-3 field-error">{{ $message }}</p>
            @enderror

            <button type="button" @click="addExpense" class="btn btn-secondary btn-sm mt-4">
                <x-icon name="plus" class="size-4" />
                إضافة مصروف
            </button>
        </div>
    </section>

    <section class="card">
        <header class="panel-head">
            <h2 class="section-title">الحساب المتوقع</h2>
            <p class="section-desc">معاينة مباشرة، يُعاد حساب القيم على الخادم عند الحفظ.</p>
        </header>

        <div class="panel-body">
            <dl class="space-y-3">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-sm text-muted-foreground">السعر</dt>
                    <dd dir="ltr" class="text-sm font-medium tabular-nums text-foreground" x-text="fmt(price)">—</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-sm text-muted-foreground">إجمالي المصروفات</dt>
                    <dd dir="ltr" class="text-sm font-medium tabular-nums text-foreground" x-text="fmt(totalExpenses)">—</dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-t border-border pt-3">
                    <dt class="text-sm font-medium text-foreground">الصافي</dt>
                    <dd dir="ltr" class="text-sm font-semibold tabular-nums text-foreground" x-text="fmt(net)">—</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-sm text-muted-foreground">
                        حصة السائق
                        <span class="text-xs text-muted-foreground/70" x-text="'(' + fmt(driverPercentage) + '%)'">(%)</span>
                    </dt>
                    <dd dir="ltr" class="text-sm font-medium tabular-nums text-foreground" x-text="fmt(driverShare)">—</dd>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-sm text-muted-foreground">حصة الشركة</dt>
                    <dd dir="ltr" class="text-sm font-medium tabular-nums text-foreground" x-text="fmt(companyShare)">—</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- Quick-add customer modal (posts to /customers/quick-store) --}}
    <div
        x-show="customerModalOpen"
        x-cloak
        @keydown.enter.prevent
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        role="dialog"
        aria-modal="true"
        class="fixed inset-0 z-50 flex items-center justify-center bg-foreground/25 p-4"
        @click.self="closeCustomerModal()"
        @keydown.escape.window="closeCustomerModal()"
    >
        <div
            class="w-full max-w-sm rounded-xl border border-border bg-surface p-6 shadow-xl"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <h2 class="text-base font-semibold text-foreground">إضافة عميل جديد</h2>
            <p class="mt-1 text-sm leading-6 text-muted-foreground">سيُضاف العميل مباشرة إلى قائمة العملاء.</p>

            <div class="mt-5 space-y-4">
                <template x-if="customerErrors.form">
                    <p class="field-error" x-text="customerErrors.form[0]"></p>
                </template>
                <div>
                    <label for="quick_name" class="label">الاسم</label>
                    <input
                        id="quick_name"
                        type="text"
                        x-model="customerForm.name"
                        @keydown.enter.prevent="quickAddCustomer()"
                        class="input"
                        :disabled="customerSubmitting"
                    >
                    <template x-if="customerErrors.name">
                        <p class="field-error" x-text="customerErrors.name[0]"></p>
                    </template>
                </div>

                <div>
                    <label for="quick_phone" class="label">
                        رقم الهاتف <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="quick_phone"
                        type="text"
                        x-model="customerForm.phone"
                        @keydown.enter.prevent="quickAddCustomer()"
                        dir="ltr"
                        class="input"
                        :disabled="customerSubmitting"
                    >
                    <template x-if="customerErrors.phone">
                        <p class="field-error" x-text="customerErrors.phone[0]"></p>
                    </template>
                </div>

                <div>
                    <label for="quick_address" class="label">
                        العنوان <span class="text-muted-foreground">(اختياري)</span>
                    </label>
                    <input
                        id="quick_address"
                        type="text"
                        x-model="customerForm.address"
                        @keydown.enter.prevent="quickAddCustomer()"
                        class="input"
                        :disabled="customerSubmitting"
                    >
                    <template x-if="customerErrors.address">
                        <p class="field-error" x-text="customerErrors.address[0]"></p>
                    </template>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <button type="button" @click="closeCustomerModal()" class="btn btn-secondary btn-sm">
                    إلغاء
                </button>
                <button
                    type="button"
                    @click="quickAddCustomer"
                    class="btn btn-primary btn-sm"
                    :disabled="customerSubmitting"
                >
                    <x-icon name="check" class="size-4" />
                    <span x-text="customerSubmitting ? 'جارٍ الإضافة…' : 'إضافة العميل'">إضافة العميل</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary" :disabled="saving">
            <x-icon name="check" class="size-4" />
            <span x-text="saving ? 'جارٍ الحفظ…' : '{{ $isEdit ? 'حفظ التغييرات' : 'إضافة الرحلة' }}'">حفظ</span>
        </button>

        <a href="{{ $isEdit ? route('admin.trips.show', $trip) : route('admin.trips.index') }}" class="btn btn-secondary">
            إلغاء
        </a>
    </div>
</form>