@props(['totals'])

{{-- Shared report totals bar; computed over the whole filtered set. --}}
<section class="card overflow-hidden" aria-label="إجماليات التقرير">
    <div class="grid grid-cols-2 gap-px bg-border sm:grid-cols-3 xl:grid-cols-6">
        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">عدد الرحلات</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ number_format($totals->trip_count) }}
            </p>
        </div>

        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">إجمالي الإيراد</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ format_number($totals->total_price) }}
            </p>
        </div>

        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">إجمالي المصروفات</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ format_number($totals->total_expenses) }}
            </p>
        </div>

        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">صافي الإجمالي</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ format_number($totals->total_net) }}
            </p>
        </div>

        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">حصة السائق</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ format_number($totals->total_driver) }}
            </p>
        </div>

        <div class="bg-surface px-5 py-4 sm:px-6">
            <p class="text-sm text-muted-foreground">حصة الشركة</p>
            <p dir="ltr" class="mt-1.5 text-right text-xl font-semibold tabular-nums text-foreground">
                {{ format_number($totals->total_company) }}
            </p>
        </div>
    </div>
</section>