<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'My Store')
        {{ isset($store) ? ' - ' . $store->name : '' }}
    </title>

    <meta name="description" content="@yield('meta_description')">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('meta_image')">

    @yield('product_schema')

    <!-- Stylesheets -->
    <link rel="stylesheet"
        href="{{ asset('assets/css/themes/' . (current_theme_name_en() ?? 'default') . '.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <!-- استيراد الخط -->
    <link
        href="https://fonts.googleapis.com/css2?family={{ urlencode(store_setting('font_family', 'Cairo')) }}:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- إعدادات الثيم -->
    <style>
         /* Light Mode */
        :root {
            --primary-color: {{ store_setting('primary_color', '#ff6f1e') }};
            --primary-color-rgb: {{ store_setting('primary_color_rgb', '255, 111, 30') }};
            --secondary-color: {{ store_setting('secondary_color', '#ff8533') }};
            --success-color: {{ store_setting('success_color', '#3ce551') }};
            --warning-color: {{ store_setting('warning_color', '#ffd42d') }};
            --danger-color: {{ store_setting('danger_color', '#ef7575') }};
            --light-bg: {{ store_setting('light_bg', '#fcfcfc') }};
            --dark-bg: {{ store_setting('dark_bg', '#252f4a') }};
            --text-primary: {{ store_setting('text_primary', '#252f4a') }};
            --text-secondary: {{ store_setting('text_secondary', '#66718e') }};
            --border-color: {{ store_setting('border_color', '#F1F1F4') }};
            --card-bg: {{ store_setting('card_bg', '#ffffff') }};
            --input-bg: {{ store_setting('input_bg', '#f9f9f9') }};
            --header-bg: {{ store_setting('header_bg', '#ffffff') }};
            --footer-bg: {{ store_setting('footer_bg', '#252f4a') }};
            --footer-text: {{ store_setting('footer_text', '#ffffff') }};
            --footer-link: {{ store_setting('footer_link', 'rgba(255,255,255,0.8)') }};
            --shadow-color: {{ store_setting('shadow_color', 'rgba(0,0,0,0.1)') }};
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg,
                    {{ store_setting('primary_color', '#ff6f1e') }} 0%,
                    {{ store_setting('secondary_color', '#ff8533') }} 100%);

            /* Legacy support */
            --primary-dark: {{ store_setting('primary_dark', '#d05d10') }};
            --primary-light: {{ store_setting('primary_light', '#ff9847') }};
            --accent-color: {{ store_setting('accent_color', '#ff6f1e') }};
            --text-dark: var(--text-primary);
            --text-light: var(--text-secondary);
            --bg-light: var(--light-bg);
            --bg-secondary: var(--input-bg);
            --white: var(--card-bg);
            --transition: var(--transition-base);
            --shadow-md: 0 4px 6px -1px var(--shadow-color);
            --shadow-lg: 0 10px 15px -3px var(--shadow-color);
            --shadow-xl: 0 20px 25px -5px var(--shadow-color);
            --shadow-sm: 0 1px 2px 0 var(--shadow-color);
        }

        /* Dark Mode */
        [data-theme="dark"],
        body.dark-mode {
            --primary-color: {{ store_setting('primary_color_dark', '#ff8533') }};
            --primary-color-rgb: {{ store_setting('primary_color_rgb_dark', '255, 133, 51') }};
            --secondary-color: {{ store_setting('secondary_color_dark', '#ff9847') }};
            --text-primary: {{ store_setting('text_primary_dark', '#f8f9fa') }};
            --text-secondary: {{ store_setting('text_secondary_dark', '#adb5bd') }};
            --light-bg: {{ store_setting('light_bg_dark', '#301c0f') }};
            --dark-bg: {{ store_setting('dark_bg_dark', '#1e0f08') }};
            --border-color: {{ store_setting('border_color_dark', '#3d2513') }};
            --card-bg: {{ store_setting('card_bg_dark', '#301c0f') }};
            --input-bg: {{ store_setting('input_bg_dark', '#3d2513') }};
            --header-bg: {{ store_setting('header_bg_dark', '#301c0f') }};
            --footer-bg: {{ store_setting('footer_bg_dark', '#1e0f08') }};
            --footer-text: {{ store_setting('footer_text_dark', '#f8f9fa') }};
            --footer-link: {{ store_setting('footer_link_dark', 'rgba(255, 133, 51, 0.8)') }};
            --shadow-color: {{ store_setting('shadow_color_dark', 'rgba(0,0,0,0.7)') }};

            /* Legacy support */
            --text-dark: var(--text-primary);
            --text-light: var(--text-secondary);
            --bg-light: var(--light-bg);
            --bg-secondary: var(--card-bg);
            --white: var(--card-bg);
            --accent-color: var(--primary-color);
        }

        body {
            font-family: '{{ store_setting('font_family', 'Cairo') }}', sans-serif !important;
        }
    </style>
</head>

<body class="modern-layout">
    @includeIf('digital.themes.' . current_theme_name_en() . '.nav')
    @includeIf('digital.themes.' . current_theme_name_en() . '.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Store Scripts -->
    <script src="{{ asset('assets/js/themes/' . (current_theme_name_en() ?? 'default') . '.js') }}?v={{ time() }}">
    </script>

    <!-- Blade pushed scripts -->
    @stack('scripts')
    <!-- Session Alerts -->
    <script>
        $(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: "{{ __('نجاح') }}",
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: "{{ __('خطأ') }}",
                    text: "{{ session('error') }}",
                    timer: 3000,
                    showConfirmButton: true
                });
            @endif
        });
    </script>
</body>

</html>
