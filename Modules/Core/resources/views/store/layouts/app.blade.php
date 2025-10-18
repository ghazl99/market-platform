<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }}</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles-store.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>

<body class="modern-layout">
    @include('core::store.layouts.nav')
    @include('core::store.layouts.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Performance optimizations -->
    <script>


        // Optimize scroll performance
        let ticking = false;

        function updateOnScroll() {
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateOnScroll);
                ticking = true;
            }
        });

        // Mobile-specific optimizations
        if (window.innerWidth <= 768) {
            document.body.classList.add('mobile-device');
            document.addEventListener('touchstart', function() {}, {
                passive: true
            });
            document.addEventListener('touchmove', function() {}, {
                passive: true
            });
        }

        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 100);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            }
        });
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Store Scripts -->
    <script src="{{ asset('assets/js/script-store.js') }}"></script>

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
