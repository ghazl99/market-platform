@extends('themes.app')

@section('title', __('Home'))
@push('styles')
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
                            @php $media = $category->getFirstMedia('category_images'); @endphp
                            <a href="{{ route('category.subCategories', $category->id) }}" class="category-card">
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
                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">سماعات لاسلكية</h3>
                    </div>

                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">ساعة ذكية</h3>
                    </div>

                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">نظارات شمسية</h3>
                    </div>

                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1492707892479-7bc8d5a4ee93?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">جهاز تحكم</h3>
                    </div>

                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">ساعة يد</h3>
                    </div>

                    <div class="featured-product">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400" alt="منتج مميز">
                        </div>
                        <h3 class="featured-name">حافظة جلدية</h3>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
