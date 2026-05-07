<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        /* Modern Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }

        /* Global Focus Reset (No Rings) */
        *:focus {
            outline: none !important;
            box-shadow: none !important;
            --tw-ring-width: 0px !important;
            --tw-ring-offset-width: 0px !important;
        }

        /* DataTables Premium Styling */
        .dataTables_wrapper {
            padding: 1rem 0;
        }
        .dataTables_length, .dataTables_filter {
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }
        .dataTables_length select, .dataTables_filter input {
            border: 1px solid #e5e7eb !important;
            border-radius: 4px !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease;
            outline: none !important;
        }
        .dataTables_length select:focus, .dataTables_filter input:focus {
            border-color: #6366f1 !important;
            background-color: #fff !important;
            box-shadow: none !important;
        }
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
        }
        table.dataTable thead th {
            background-color: #f8fafc !important;
            padding: 0.75rem 1.5rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.025em !important;
            color: #64748b !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        table.dataTable tbody td {
            padding: 1rem 1.5rem !important;
            font-size: 0.875rem !important;
            color: #334155 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle !important;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
            transition: background-color 0.2s ease;
        }
        .dataTables_info, .dataTables_paginate {
            padding: 1rem 1.5rem !important;
            font-size: 0.875rem !important;
            color: #64748b !important;
        }
        .pagination {
            gap: 0.25rem !important;
        }
        .page-item.active .page-link {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            border-radius: 4px !important;
            color: #ffffff !important;
        }
        .page-link {
            border-radius: 4px !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
            padding: 0.5rem 0.75rem !important;
            transition: all 0.2s ease;
        }
        .page-link:hover {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
        }
        .page-link:focus {
            box-shadow: none !important;
            background-color: #f8fafc !important;
        }

        /* Utility Classes */
        .badge-pill {
            padding: 0.25rem 0.75rem !important;
            border-radius: 9999px !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
        }
        
        /* Form Control Global Consistency */
        input:focus, select:focus, textarea:focus, button:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #6366f1 !important;
        }
        
        .form-control, .form-select {
            border-radius: 4px !important;
            border: 1px solid #e5e7eb !important;
            transition: all 0.2s ease;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable();
        });

        $(document).on('click', '.btn-action', function() {
            const title = $(this).data('confirm-title') || "Konfirmasi";
            const text = $(this).data('confirm-text') || "Apakah Anda yakin?";
            const icon = $(this).data('confirm-icon') || "question";
            const action = $(this).data('action');
            const method = $(this).data('method') || "POST";

            if (!action) return;

            Swal.fire({
                title,
                text,
                icon,
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
                confirmButtonColor: '#4f46e5', // Indigo-600
                cancelButtonColor: '#d1d5db', // Gray-300
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $(`<form action="${action}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="${method}">
                    </form>`);
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
