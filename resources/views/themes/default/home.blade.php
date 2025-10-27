@extends('themes.app')

@section('title', __('Home'))

@push('styles')
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
    --gradient-primary: linear-gradient(
        135deg,
        {{ store_setting('primary_color', '#ff6f1e') }} 0%,
        {{ store_setting('secondary_color', '#ff8533') }} 100%
    );

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


        .category-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            background: #e9ecef;
            /* خلفية خفيفة للصورة الافتراضية */
        }

        .default-store-svg svg {
            width: 100%;
            height: 100%;
        }

        .service-card {
            display: block;
            text-decoration: none;
            color: inherit;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .service-image {
            width: 100%;
            height: 200px;
        }

        .service-content {
            padding: 0.5rem 1rem 1rem;
            text-align: center;
        }
    </style>
@endpush

@section('content')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-pattern"></div>
            <div class="floating-elements">
                <div class="floating-shape shape-1"></div>
                <div class="floating-shape shape-2"></div>
                <div class="floating-shape shape-3"></div>
                <div class="floating-shape shape-4"></div>
            </div>
        </div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-badge">
                        <i class="fas fa-star"></i>
                        <span>{{ __('Best Digital Services') }}</span>
                    </div>

                    <h1 class="hero-title">
                        <span class="title-line">{{ $store->name }}</span>
                    </h1>

                    <p class="hero-description">{{ $store->description }}
                    </p>

                    <div class="hero-actions">
                        <button class="btn btn-primary btn-lg">
                            <i class="fas fa-rocket"></i>
                            <span>{{ __('Start Now') }}</span>
                        </button>
                        <button class="btn btn-outline btn-lg">
                            <i class="fas fa-play"></i>
                            <span>{{ __('Watch Video') }}</span>
                        </button>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">{{ __('Happy Clients') }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">100+</div>
                            <span>{{ __('Game Top-Up') }}</span>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <span>{{ __('Discover More') }}</span>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-image-container">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&h=400&fit=crop"
                            alt="الخدمات الرقمية" class="hero-image">

                    </div>
                </div>
            </div>
        </div>

        <div class="hero-scroll">
            <div class="scroll-indicator">
                <span>{{ __('Discover More') }}</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="services-header">
                <h2 class="section-title">{{ __('Our Services') }}</h2>
            </div>

            <div class="services-grid">
                @if ($categories->count() > 0)
                    @foreach ($categories as $category)
                        <a href="{{ route('category.subCategories', $category->id) }}" class="service-card">
                            @php $media = $category->getFirstMedia('category_images'); @endphp
                            <div class="service-image">
                                @if ($media)
                                    <img src="{{ route('category.image', $media->id) }}" alt="{{ $category->name }}"
                                        class="category-img">
                                @else
                                    <div class="category-img default-store-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                            <!-- لافتة المحل -->
                                            <rect x="8" y="12" width="48" height="6" fill="#6c757d"
                                                rx="1" />
                                            <text x="32" y="16" font-size="4" text-anchor="middle" fill="#fff"
                                                font-family="Arial, sans-serif">STORE</text>
                                            <!-- واجهة المحل -->
                                            <rect x="6" y="20" width="52" height="36" fill="#e9ecef" rx="2"
                                                stroke="#adb5bd" stroke-width="1" />
                                            <!-- باب المحل -->
                                            <rect x="28" y="40" width="8" height="16" fill="#343a40"
                                                rx="1" />
                                            <!-- نوافذ المحل -->
                                            <rect x="12" y="26" width="8" height="12" fill="#ced4da"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <rect x="44" y="26" width="8" height="12" fill="#ced4da"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <line x1="16" y1="26" x2="16" y2="38"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <line x1="12" y1="32" x2="20" y2="32"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <line x1="48" y1="26" x2="48" y2="38"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <line x1="44" y1="32" x2="52" y2="32"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="service-content">
                                <h3>{{ $category->name }}</h3>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="alert alert-info text-center">
                        {{ __('No products found for this category.') }}
                    </div>
                @endif
            </div>

        </div>
    </section>

@endsection
