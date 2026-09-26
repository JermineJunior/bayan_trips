{{-- Print footer: optional closing message, then the filters applied to this
     report and the generation timestamp. Every line is omitted when empty. --}}
<footer class="print-footer mt-8 border-t border-neutral-300 pt-3 text-xs text-neutral-600">
    @if ($reportsMessage)
        <p class="mb-2">{{ $reportsMessage }}</p>
    @endif

    <div class="flex items-center justify-between gap-4">
        <p>
            @if ($filterSummary)
                عوامل التصفية: {{ implode(' • ', $filterSummary) }}
            @else
                جميع السجلات
            @endif
        </p>
        <p dir="ltr">{{ $generatedAt }}</p>
    </div>
</footer>