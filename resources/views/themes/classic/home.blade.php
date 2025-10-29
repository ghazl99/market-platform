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
@endpush
@section('content')
    <!-- Main Content -->
    <main class="main-content-adjust">
        <!-- Hero Section with Slider -->
        @php
            $banners = $store->getMedia('banner');
            $storeSettings = $store->settings ?? [];
            $primaryColor = $storeSettings['primary-color'] ?? '#059669';
        @endphp

        <section class="hero" id="home">
            <div class="hero-slider" id="heroSlider">
                @forelse ($banners as $index => $banner)
                    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                        <div class="slide-background"
                            style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}cc 100%);">
                        </div>
                        <div class="hero-image">
                            <img src="{{ route('store.image', $banner->id) }}" alt="Banner {{ $index + 1 }}">
                        </div>
                    </div>
                @empty
                    {{-- في حال ما في بنرات --}}
                    <div class="hero-slide active" data-slide="0">
                        <div class="slide-background"
                            style="background: linear-gradient(135deg, #ff6f1e 0%, #ff8533 100%);">
                        </div>
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1556740758-90de374c12ad?w=1200"
                                alt="Default Banner">
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Slider Navigation -->
            <div class="hero-navigation">
                <button class="prev-btn" id="prevSlide">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="next-btn" id="nextSlide">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <!-- Indicators -->
            <div class="hero-indicators" id="heroIndicators">
                @foreach ($banners as $index => $banner)
                    <span class="indicator {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></span>
                @endforeach
                @if ($banners->isEmpty())
                    <span class="indicator active" data-slide="0"></span>
                @endif
            </div>
        </section>


        <!-- Promotional Banner - Scrolling Text -->
        <section class="promo-banner">
            <div class="promo-scroll">
                <div class="promo-marquee">
                    <div class="marquee-content">
                        <span class="scroll-text">{{ $store->description }}</span>

                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories" id="categories">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">تسوق حسب الفئة</h2>
                    <p class="section-subtitle">استكشف مجموعتنا الواسعة من المنتجات</p>
                </div>

                <div class="categories-grid">

                    @if ($categories->count() > 0)
                        @foreach ($categories as $category)
                            @php
                                $media = $category->getFirstMedia('category_images');
                                $hasProducts = $category->products()->exists();

                                // تحديد الرابط المناسب
                                if ($hasProducts) {
                                    $url = route('category.show', $category->id);
                                } else {
                                    $url = route('category.subCategories', $category->id);
                                }
                            @endphp
                            <a href="{{ $url }}" class="category-card" style="text-decoration: none">
                                @if ($media)
                                    <div class="category-image">

                                        <img src="{{ route('category.image', $media->id) }}" alt="{{ $category->name }}">
                                    </div>
                                @endif
                                <h3>{{ $category->name }}</h3>
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

        <!-- Featured Products Section -->
        <section class="featured-products">
            <div class="container">
                <div class="featured-header">
                    <h2 class="featured-title">
                        <span class="title-line"></span>
                        <span class="title-text">منتجات مميزة</span>
                        <span class="title-line"></span>
                    </h2>
                    <p class="featured-description">اكتشف أفضل منتجاتنا المختارة بعناية</p>
                </div>

                <div class="featured-grid">
                    @forelse ($topViewed as $product)
                        <div class="featured-product">
                            <div class="featured-image">
                                @php $media = $product->getFirstMedia('product_images'); @endphp

                                @if ($media)
                                    <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('assets/images/placeholder.png') }}" alt="No Image">
                                @endif
                            </div>
                            <h3 class="featured-name">
                                {{ $product->name['ar'] ?? $product->name['en'] }}
                            </h3>
                            <p class="featured-price">{{ number_format($product->price, 2) }} {{ __('ريال') }}</p>
                        </div>
                    @empty
                        <p class="text-center w-full">لا توجد منتجات مميزة حالياً</p>
                    @endforelse
                </div>
            </div>
        </section>


    </main>
@endsection
