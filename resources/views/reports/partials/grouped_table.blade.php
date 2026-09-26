@props([
    'rows',
    'totals',
    'showAvgs' => false,
])

{{-- Shared grouped-report table. Renders identically on screen and in print. --}}
<table class="table">
    <thead>
        <tr>
            <th class="text-start">المجموعة</th>
            <th class="text-right">عدد الرحلات</th>
            <th class="text-right">الإيراد</th>
            @if ($showAvgs)
                <th class="text-right">متوسط الإيراد</th>
            @endif
            <th class="text-right">المصروفات</th>
            <th class="text-right">الصافي</th>
            @if ($showAvgs)
                <th class="text-right">متوسط الصافي</th>
            @endif
            <th class="text-right">إجمالي العمولة</th>
            <th class="text-right">إجمالي الشركة</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr>
                <td>
                    @if ($row['link'])
                        <a href="{{ $row['link'] }}" class="link">
                            {{ $row['title'] }}
                        </a>
                    @else
                        {{ $row['title'] }}
                    @endif
                </td>
                <td class="whitespace-nowrap tabular-nums text-foreground">
                    {{ number_format((int) $row['trip_count']) }}
                </td>
                <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-foreground">
                    {{ format_number($row['total_price']) }}
                </td>
                @if ($showAvgs)
                    <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-muted-foreground">
                        {{ format_number($row['avg_price']) }}
                    </td>
                @endif
                <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-muted-foreground">
                    {{ format_number($row['total_expenses']) }}
                </td>
                <td dir="ltr" class="whitespace-nowrap text-right font-medium tabular-nums text-foreground">
                    {{ format_number($row['total_net']) }}
                </td>
                @if ($showAvgs)
                    <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-muted-foreground">
                        {{ format_number($row['avg_net']) }}
                    </td>
                @endif
                <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-muted-foreground">
                    {{ format_number($row['total_driver']) }}
                </td>
                <td dir="ltr" class="whitespace-nowrap text-right tabular-nums text-muted-foreground">
                    {{ format_number($row['total_company']) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $showAvgs ? 9 : 7 }}" class="text-center text-muted-foreground">
                    لا توجد رحلات مطابقة
                </td>
            </tr>
        @endforelse
    </tbody>
    @if ($rows->isNotEmpty())
        <tfoot>
            <tr>
                <th class="text-start">الإجمالي</th>
                <th class="whitespace-nowrap tabular-nums">{{ number_format((int) $totals->trip_count) }}</th>
                <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->total_price) }}</th>
                @if ($showAvgs)
                    <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->avg_price) }}</th>
                @endif
                <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->total_expenses) }}</th>
                <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->total_net) }}</th>
                @if ($showAvgs)
                    <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->avg_net) }}</th>
                @endif
                <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->total_driver) }}</th>
                <th dir="ltr" class="whitespace-nowrap text-right tabular-nums">{{ format_number($totals->total_company) }}</th>
            </tr>
        </tfoot>
    @endif
</table>