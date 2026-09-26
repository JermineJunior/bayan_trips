@props(['from' => null, 'to' => null])

{{-- Selected date range banner shown right after the report header. Renders
     nothing when no date range is applied, and the row stays compact when
     only one bound is set. --}}
@php
    $from = $from ?? request('date_from');
    $to = $to ?? request('date_to');
@endphp

@if ($from || $to)
    <div class="inline-flex flex-wrap items-center gap-x-2 gap-y-1 rounded-lg border border-border bg-muted/40 px-3 py-2 text-sm text-foreground">
        <x-icon name="clock" class="size-4 text-muted-foreground" />
        <span class="font-medium text-muted-foreground">الفترة:</span>
        <span dir="ltr" class="tabular-nums">
            من {{ $from ?: '…' }}
            <span class="mx-1 text-muted-foreground/60">←</span>
            إلى {{ $to ?: '…' }}
        </span>
    </div>
@endif