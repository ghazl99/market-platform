<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }}</title>

    <!-- Mobile optimizations -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="لوحة تحكم المتجر">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#059669">
    <meta name="msapplication-TileColor" content="#059669">

    <!-- Icons -->
    <link rel="apple-touch-icon" href="../Digitaltheme/images/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Digitaltheme/images/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Digitaltheme/images/icon-16x16.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles-store.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body class="loaded">
    <div class="dashboard-container">
        @include('core::dashboard.layouts.sidebar')
        <!-- Main Content -->
        <main class="dashboard-main" id="mainContent">
            @include('core::dashboard.layouts.nav')
            @yield('content')
        </main>
    </div>
    @stack('scripts')

    <script src="{{ asset('assets/js/script-store.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>

</html>
