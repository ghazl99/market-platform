<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        use Modules\Store\Models\Store;
        $pageStore = Store::currentFromUrl()->first();
    @endphp
    <title>{{ $pageStore ? $pageStore->name : 'Market Platform' }}</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/themes/default.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-notifications.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')

    <!-- Apply theme immediately to prevent flash of wrong theme -->
    <script>
        (function() {
            // Get saved theme from localStorage (check both keys for compatibility)
            const savedTheme = localStorage.getItem('dashboard-theme') || 
                              localStorage.getItem('theme') || 
                              'light';
            const html = document.documentElement;
            
            // Apply theme immediately before page renders (synchronously)
            html.setAttribute('data-theme', savedTheme);
            
            // Apply to body when it's available (use DOMContentLoaded as fallback)
            if (document.body) {
                if (savedTheme === 'dark') {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
            } else {
                // Body not ready yet, wait for it
                document.addEventListener('DOMContentLoaded', function() {
                    if (savedTheme === 'dark') {
                        document.body.classList.add('dark-mode');
                    } else {
                        document.body.classList.remove('dark-mode');
                    }
                });
            }
        })();
    </script>
    
    <!-- Critical CSS to prevent flash - Apply theme-based styles immediately -->
    <style id="critical-theme-styles">
        /* These styles prevent flash of wrong theme */
        html[data-theme="dark"] body,
        html[data-theme="dark"] .dashboard-container,
        html[data-theme="dark"] .dashboard-main {
            background: #1a1a1a !important;
            color: #ffffff !important;
        }
        
        html[data-theme="light"] body,
        html[data-theme="light"] .dashboard-container,
        html[data-theme="light"] .dashboard-main {
            background: #ffffff !important;
            color: #111827 !important;
        }
        
        /* Dark mode product cards */
        html[data-theme="dark"] .product-card {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        
        /* Light mode product cards */
        html[data-theme="light"] .product-card {
            background: #ffffff !important;
            border-color: #e5e7eb !important;
            color: #111827 !important;
        }
    </style>
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
    <script src="{{ asset('assets/js/notifications.js') }}?v={{ time() }}" onload="handleNotificationsLoaded()"></script>
    <script src="{{ asset('assets/js/dashboard-notifications.js') }}?v={{ time() }}" defer></script>
    
    <!-- Handle session messages after notifications.js loads -->
    <script>
        @if (session('success'))
            const successMessage = '{{ session('success') }}';
            let notificationShown = false;
            
            function handleNotificationsLoaded() {
                if (!notificationShown && typeof window.showSuccess === 'function') {
                    notificationShown = true;
                    window.showSuccess('تم بنجاح!', successMessage);
                }
            }
            
            // محاولة عرض الإشعار بعد تحميل notifications.js
            function showSuccessNotification() {
                if (!notificationShown) {
                    if (typeof window.showSuccess === 'function') {
                        notificationShown = true;
                        window.showSuccess('تم بنجاح!', successMessage);
                    } else if (typeof showSuccess === 'function') {
                        notificationShown = true;
                        showSuccess('تم بنجاح!', successMessage);
                    } else {
                        // محاولة أخرى بعد 100ms (بحد أقصى 20 محاولة)
                        if (typeof showSuccessNotification.attempts === 'undefined') {
                            showSuccessNotification.attempts = 0;
                        }
                        showSuccessNotification.attempts++;
                        
                        if (showSuccessNotification.attempts < 20) {
                            setTimeout(showSuccessNotification, 100);
                        }
                    }
                }
            }
            
            // محاولة فورية
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(showSuccessNotification, 200);
                });
            } else {
                setTimeout(showSuccessNotification, 200);
            }
            
            // أيضاً محاولة مع window.load
            window.addEventListener('load', function() {
                setTimeout(showSuccessNotification, 300);
            });
        @endif
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Suppress browser extension errors
        if (typeof chrome !== 'undefined' && chrome.runtime) {
            chrome.runtime.onMessage && chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
                try {
                    // Handle extension messages if needed
                } catch (e) {
                    // Silently ignore extension errors
                }
                return true;
            });
        }

        // Global error handler to suppress extension-related errors
        window.addEventListener('error', function(e) {
            // Suppress known extension errors
            if (e.message && (
                e.message.includes('runtime.lastError') ||
                e.message.includes('extension port') ||
                e.message.includes('message channel')
            )) {
                e.preventDefault();
                return false;
            }
        });

        // Handle session messages (تم نقلها إلى بعد notifications.js مباشرة)
        $(document).ready(function() {
            try {

                @if (session('error'))
                    if (typeof showError === 'function') {
                        showError('حدث خطأ!', '{{ session('error') }}');
                    }
                @endif

                @if (session('warning'))
                    if (typeof showWarning === 'function') {
                        showWarning('تحذير!', '{{ session('warning') }}');
                    }
                @endif

                @if (session('info'))
                    if (typeof showInfo === 'function') {
                        showInfo('معلومة', '{{ session('info') }}');
                    }
                @endif

                // Handle validation errors
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        if (typeof showError === 'function') {
                            showError('خطأ في التحقق', '{{ $error }}');
                        }
                    @endforeach
                @endif

                // Delete confirmation is handled by individual pages
            } catch (error) {
                console.warn('Error in document ready handler:', error);
            }
        });
    </script>

</body>

</html>
