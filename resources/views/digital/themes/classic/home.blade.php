@extends('digital.themes.app')

@section('title', __('Home'))
@push('styles')
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
                    <h2 class="section-title">{{ __('Shop by Category') }}</h2>
                    <p class="section-subtitle">{{ __('Explore our wide range of products') }}</p>

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
                        <span class="title-text">{{ __('Featured Products') }}</span>
                        <span class="title-line"></span>
                    </h2>
                    <p class="featured-description">{{ __('Discover our carefully selected best products') }}</p>
                </div>

                <div class="featured-grid">
                    @forelse ($topViewed as $product)
                        <a href="{{ route('product.show', $product->id) }}" class="featured-product"
                            style="text-decoration: none">
                            <div class="featured-image">
                                @php $media = $product->getFirstMedia('product_images'); @endphp

                                @if ($media)
                                    <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}">
                                @endif
                            </div>
                            <h3 class="featured-name">
                                {{ $product->name }}
                            </h3>
                        </a>
                    @empty
                        <p class="text-center w-full">{{ __('No featured products currently available') }}</p>
                    @endforelse
                </div>
            </div>
        </section>


    </main>
@endsection
