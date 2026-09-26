<!DOCTYPE html>
<html
    lang="ar"
    dir="rtl"
    data-theme="light"
    data-font-size="default"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', $appName)</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css'])

        <style>
            /* Print views are always rendered as light documents, regardless of
               the authenticated user's theme, so printed output is readable. */
            @media print {
                * {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                html,
                body {
                    background: #ffffff !important;
                    color: #171717 !important;
                    overflow: visible !important;
                    height: auto !important;
                }

                /* Never clip printed content: any scroll container's overflow
                   must become visible so the full table renders, not a cut
                   viewport. Applies to the table and every ancestor container
                   up to the page root. */
                .overflow-x-auto,
                .overflow-y-auto,
                .overflow-auto,
                .overflow-hidden,
                .table-wrap,
                table,
                thead,
                tbody,
                tfoot,
                tr,
                td,
                th {
                    overflow: visible !important;
                    max-height: none !important;
                    height: auto !important;
                }

                /* Repeat the report header on every page and keep the totals
                   row attached to the table so they never separate from their
                   rows across a page break. */
                table {
                    width: 100% !important;
                    border-collapse: collapse;
                }

                thead {
                    display: table-header-group;
                }

                tfoot {
                    display: table-footer-group;
                }

                tr,
                th,
                td {
                    page-break-inside: avoid;
                    break-inside: avoid;
                }

                .print-toolbar {
                    display: none !important;
                }

                .print-toolbar + * {
                    padding-top: 0 !important;
                }
            }
        </style>
    </head>

    <body class="bg-background text-foreground antialiased">
        {{-- Toolbar: hidden from the printed page itself. --}}
        <div class="print-toolbar sticky top-0 z-50 flex items-center gap-2 border-b border-border bg-surface/95 px-4 py-3 backdrop-blur-sm">
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <x-icon name="printer" class="size-4" />
                طباعة
            </button>
            <button type="button" onclick="window.close()" class="btn btn-secondary">
                إغلاق
            </button>
            <p class="ms-auto hidden text-xs text-muted-foreground sm:block">
                استخدم زر الطباعة أو Ctrl+P لطباعة التقرير.
            </p>
        </div>

        <main class="mx-auto w-full max-w-5xl px-8 py-8 print:max-w-none print:px-0 print:py-0">
            @yield('content')
        </main>

        <script>
            window.addEventListener('load', function () {
                setTimeout(function () { window.print(); }, 500);
            });
        </script>
    </body>
</html>