<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ __('Platform Name-The best solution for e-commerce') }}</title>
    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap"
        as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/main-dashboard-style.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">


    @stack('styles')
</head>

<body class="modern-layout">


    <!-- Navigation -->
    @include('core::store.layouts.nav')

    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="container mt-5 pt-5">
                <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-5 pt-5">
                <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    @stack('scripts')
    <!-- Performance optimizations -->
    <script>
        // Preload critical images
        const criticalImages = [
            // Add any critical images here if needed
        ];

        criticalImages.forEach(src => {
            const img = new Image();
            img.src = src;
        });

        // Optimize scroll performance
        let ticking = false;

        function updateOnScroll() {
            // Scroll-based animations and effects
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
            // Disable hover effects on mobile
            document.addEventListener('touchstart', function() {}, true);

            // Optimize animations for mobile
            const style = document.createElement('style');
            style.textContent = `
                @media (max-width: 768px) {
                    * {
                        animation-duration: 10s !important;
                        transition-duration: 2s !important;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

</body>

</html>
