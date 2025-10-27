<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }}</title>

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
        :root {
            --primary-color: {{ store_setting('primary_color', '#059669') }};
            --gradient-primary: linear-gradient(135deg, {{ store_setting('primary_color', '#059669') }} 0%, {{ store_setting('primary_color', '#059669') }} 100%);
        }

        body {
            font-family: '{{ store_setting('font_family', 'Cairo') }}', sans-serif !important;
        }
    </style>
</head>

<body class="modern-layout">
    @includeIf('themes.' . current_theme_name_en() . '.nav')
    @includeIf('themes.' . current_theme_name_en() . '.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Store Scripts -->
    <script src="{{ asset('assets/js/themes/' . (current_theme_name_en() ?? 'default') . '.js') }}?v={{ time() }}"></script>

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
