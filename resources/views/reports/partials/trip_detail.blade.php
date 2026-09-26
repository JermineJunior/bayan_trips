@props(['trip'])

{{-- Full breakdown of one trip, shared by the on-screen and print versions. --}}
<section class="space-y-6">
    <div class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-3">
        <div>
            <p class="text-xs text-muted-foreground">تاريخ الرحلة</p>
            <p class="font-medium">{{ $trip->trip_date->translatedFormat('l، j F Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-muted-foreground">نوع الرحلة</p>
            <p class="font-medium">{{ $trip->tripType?->name ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-muted-foreground">المركبة</p>
            <p class="font-medium">{{ $trip->vehicle?->label ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-muted-foreground">السائق</p>
            <p class="font-medium">{{ $trip->driver?->name ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-muted-foreground">العميل</p>
            <p class="font-medium">{{ $trip->customer?->name ?? 'بدون عميل' }}</p>
        </div>
        <div>
            <p class="text-xs text-muted-foreground">المسار</p>
            <p class="font-medium">{{ $trip->from_location }} ← {{ $trip->to_location }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-border bg-surface p-4">
            <p class="text-xs text-muted-foreground">إجمالي الإيراد</p>
            <p class="mt-1 text-right text-xl font-bold" dir="ltr">{{ format_number($trip->price) }}</p>
        </div>
        <div class="rounded-xl border border-border bg-surface p-4">
            <p class="text-xs text-muted-foreground">إجمالي المصروفات</p>
            <p class="mt-1 text-right text-xl font-bold" dir="ltr">{{ format_number($trip->total_expenses) }}</p>
        </div>
        <div class="rounded-xl border border-border bg-surface p-4">
            <p class="text-xs text-muted-foreground">صافي الرحلة</p>
            <p class="mt-1 text-right text-xl font-bold" dir="ltr">{{ format_number($trip->net_amount) }}</p>
        </div>
        <div class="rounded-xl border border-border bg-surface p-4">
            <p class="text-xs text-muted-foreground">نسبة السائق</p>
            <p class="mt-1 text-xl font-bold">{{ $trip->driver_percentage }}%</p>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-sm font-semibold text-muted-foreground">مصروفات الرحلة</h3>
        @if ($trip->expenses->isEmpty())
            <p class="text-sm text-muted-foreground">لا توجد مصروفات مسجلة لهذه الرحلة.</p>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-start">الوصف</th>
                            <th class="text-start">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trip->expenses as $expense)
                            <tr>
                                <td>{{ $expense->description }}</td>
                                <td dir="ltr" class="text-right">{{ format_number($expense->amount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-start">الإجمالي</th>
                            <th dir="ltr" class="text-right">{{ format_number($trip->total_expenses) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <div>
        <h3 class="mb-2 text-sm font-semibold text-muted-foreground">توزيع الصافي</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl border border-border bg-surface p-4">
                <p class="text-xs text-muted-foreground">عمولة السائق ({{ $trip->driver_percentage }}%)</p>
                <p class="mt-1 text-right text-xl font-bold" dir="ltr">{{ format_number($trip->driver_amount) }}</p>
            </div>
            <div class="rounded-xl border border-border bg-surface p-4">
                <p class="text-xs text-muted-foreground">حصّة الشركة</p>
                <p class="mt-1 text-right text-xl font-bold" dir="ltr">{{ format_number($trip->company_amount) }}</p>
            </div>
        </div>
    </div>
</section>