<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables (Bootstrap 5 skin) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js'])

    <!-- Translations for JavaScript -->
    <script>
        window.translations = {
            no_notifications: @json(__('dashboard.No notifications')),
            mark_all_read: @json(__('dashboard.Mark all as read')),
            new_product_assigned: @json(__('dashboard.New Product Assigned')),
            product_unassigned: @json(__('dashboard.Product Unassigned'))
        };
        window.routes = {
            notificationsIndex: @json(route('admin_notifications_index')),
            notificationsRead: @json(route('admin_notifications_read', ['id' => 0])),
            notificationsReadAll: @json(route('admin_notifications_read_all')),
            notificationsCount: @json(route('admin_notifications_count')),
        };
    </script>
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

        <!-- Page Content with Sidebar -->
        @php($isRtl = app()->getLocale() === 'ar')
        <div class="{{ $isRtl ? 'd-flex flex-row-reverse' : 'd-flex' }}">
            @if (View::exists('partials.sidebar'))
                @include('partials.sidebar')
            @endif
            <main class="flex-grow-1 p-3">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle (for navbar/dropdowns/modals) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>