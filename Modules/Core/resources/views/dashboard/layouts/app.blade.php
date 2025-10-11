<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($store->name) ? $store->name : 'Market Platform' }}</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/styles-store.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('assets/js/script-store.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/notifications.js') }}?v={{ time() }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Handle session messages with new notification system
            @if (session('success'))
                showSuccess('تم بنجاح!', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showError('حدث خطأ!', '{{ session('error') }}');
            @endif

            @if (session('warning'))
                showWarning('تحذير!', '{{ session('warning') }}');
            @endif

            @if (session('info'))
                showInfo('معلومة', '{{ session('info') }}');
            @endif

            // Handle validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showError('خطأ في التحقق', '{{ $error }}');
                @endforeach
            @endif

            // Delete confirmation is handled by individual pages
        });
    </script>

</body>

</html>
