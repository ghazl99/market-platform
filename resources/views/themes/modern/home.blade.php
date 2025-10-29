@extends('themes.app')

@section('title', __('Home'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home/modern.css') }}?v={{ time() }}">
    <style>
        .nav-item.active {
            background: {{ store_setting('primary_color') }};
        }
    </style>
@endpush
@section('content')
    <!-- Main Content -->
    <!-- Slideshow -->
    <section class="slideshow">
        @php
            $banners = $store->getMedia('banner');
            $storeSettings = $store->settings ?? [];
            $primaryColor = $storeSettings['primary-color'] ?? '#059669';
        @endphp

        @foreach ($banners as $index => $banner)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ route('store.image', $banner->id) }}" alt="Slide {{ $index + 1 }}">
            </div>
        @endforeach

        <div class="slide-dots">
            @foreach ($banners as $index => $banner)
                <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></span>
            @endforeach
        </div>
    </section>

    <!-- Marquee -->
    <section class="marquee">
        <marquee behavior="scroll" direction="right">
            شركة ون كليك بي : نقدم لكم خدمة الشحن الى جميع تطبيقات التواصل الاجتماعي و الالعاب والبطائق الاكترونية
            ويمكنكم تغذية حسابكم في المنصه عبر الحوالات المالية من والى كافة انحاء العالم بسرعة وبسهولة، مع إمكانية سحب
            الرواتب من جميع التطبيقات. نسعى دائماً لتقديم أفضل الخدمات لكم على مدار الساعة
        </marquee>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="category-grid">
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
                    <a href="{{ $url }}" class="category-item" style="text-decoration: none">
                        @if ($media)
                            <img src="{{ route('category.image', $media->id) }}" alt="{{ $category->name }}">
                        @endif
                        <span>{{ $category->name }} </span>
                    </a>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    {{ __('No products found for this category.') }}
                </div>
            @endif

        </div>
    </section>

    <!-- Offers -->
    <section class="offers">
        <h2>أحدث العروض</h2>
        <div class="offer-card">
            <div class="offer-header">
                <img src="https://1click-pay1.com/images/logo3.png" alt="1Click Pay" class="offer-logo">
                <div class="offer-info">
                    <h4>1Click Pay</h4>
                    <p>الأدارة</p>
                </div>
            </div>
            <div class="offer-content">
                <p>يوجد تخفيض على كل المنتجات</p>
            </div>
        </div>
    </section>
@endsection
