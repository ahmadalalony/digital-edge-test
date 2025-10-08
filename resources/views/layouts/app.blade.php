<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin Dashboard')</title>

    @if (app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100 {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}">
    {{-- Header --}}
    @include('partials.header')

    <div class="container-fluid">
        <div class="row flex-nowrap">
            {{-- Sidebar --}}
            <div class="col-auto px-0 bg-dark">
                @include('partials.sidebar')
            </div>

            {{-- Main Content --}}
            <main class="col py-4 bg-light" style="min-height: 100vh;">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>