<?php

if (! function_exists('format_number')) {
    /**
     * Format a number for display: thousands separator ',' and '.' for
     * decimals. Whole numbers render without decimals; otherwise up to
     * $decimals are shown with trailing zeros stripped. Negative values keep
     * the minus sign.
     */
    function format_number(float|int|string|null $value, int $decimals = 2): string
    {
        $formatted = number_format((float) $value, $decimals, '.', ',');

        return rtrim(rtrim($formatted, '0'), '.');
    }
}