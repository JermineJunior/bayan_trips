@extends('layouts.app')

@section('title', 'العملاء')

@section('content')
    <div class="content-container">
        <x-page-header
            title="العملاء"
            :description="number_format($customers->total()) . ' عميل مسجل في النظام'"
        >
            @can('create', App\Models\Customer::class)
                <x-slot:actions>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                        <x-icon name="contact" class="size-4" />
                        إضافة عميل
                    </a>
                </x-slot:actions>
            @endcan
        </x-page-header>

        @if (session('status'))
            <x-alert class="mt-6" :dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        <div class="mt-8">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4">
                <div class="flex flex-wrap items-center gap-2">
                    <label for="search" class="sr-only">البحث في العملاء</label>
                    <div class="relative min-w-0 flex-1">
                        <x-icon
                            name="search"
                            class="pointer-events-none absolute start-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <input
                            id="search"
                            name="search"
                            type="search"
                            value="{{ request('search') }}"
                            placeholder="ابحث بالاسم أو رقم الهاتف…"
                            class="input ps-9"
                        >
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        بحث
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost">
                            <x-icon name="x" class="size-4" />
                            مسح البحث
                        </a>
                    @endif
                </div>
            </form>

            @if ($customers->isNotEmpty())
                <div class="table-wrap">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>العميل</th>
                                    <th>رقم الهاتف</th>
                                    <th>العنوان</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
                                                    {{ mb_substr($customer->name, 0, 1) }}
                                                </span>
                                                <p class="truncate font-medium text-foreground">{{ $customer->name }}</p>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-muted-foreground">
                                            {{ $customer->phone ?: '—' }}
                                        </td>
                                        <td class="text-muted-foreground">{{ $customer->address ?: '—' }}</td>
                                        <td>
                                            <div class="flex items-center justify-end gap-0.5">
                                                @can('update', $customer)
                                                    <a
                                                        href="{{ route('admin.customers.edit', $customer) }}"
                                                        class="btn btn-ghost btn-icon"
                                                        title="تعديل العميل"
                                                        aria-label="تعديل {{ $customer->name }}"
                                                    >
                                                        <x-icon name="pencil" class="size-4" />
                                                    </a>
                                                @endcan

                                                @can('delete', $customer)
                                                    <x-confirm-modal
                                                        :action="route('admin.customers.destroy', $customer)"
                                                        method="DELETE"
                                                        tone="danger"
                                                        confirm-icon="trash"
                                                        :title="'حذف العميل ' . $customer->name"
                                                        :description="'سيتم حذف العميل. لا يمكن التراجع عن هذا الإجراء.'"
                                                        confirm-label="حذف"
                                                    >
                                                        <x-slot:trigger>
                                                            <button
                                                                type="button"
                                                                @click="open = true"
                                                                class="btn btn-ghost btn-icon text-danger hover:bg-danger/10 hover:text-danger"
                                                                title="حذف العميل"
                                                                aria-label="حذف {{ $customer->name }}"
                                                            >
                                                                <x-icon name="trash" class="size-4" />
                                                            </button>
                                                        </x-slot:trigger>
                                                    </x-confirm-modal>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($customers->hasPages())
                        <div class="border-t border-border px-4 py-3">
                            {{ $customers->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <x-empty-state
                        icon="contact"
                        :title="request('search') ? 'لا توجد نتائج مطابقة' : 'لا يوجد عملاء بعد'"
                        :description="request('search')
                            ? 'لم نعثر على أي عميل يطابق البحث، جرّب كلمات مختلفة.'
                            : 'سيظهر العملاء هنا بمجرد إضافتهم، ويمكنك إضافة أول عميل الآن.'"
                    >
                        @can('create', App\Models\Customer::class)
                            <x-slot:action>
                                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                                    <x-icon name="contact" class="size-4" />
                                    إضافة عميل
                                </a>
                            </x-slot:action>
                        @endcan
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection