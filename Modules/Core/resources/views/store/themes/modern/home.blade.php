@extends('core::store.layouts.app')

@section('title', __('Home'))
@push('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/home/modern.css') }}?v={{ time() }}">
@endpush
@section('content')
    <!-- Main Content -->
    <!-- Slideshow -->
    <section class="slideshow">
        <div class="slide active">
            <img src="https://1click-pay1.com/images/1747605229.webp" alt="Slide 1">
        </div>
        <div class="slide">
            <img src="https://1click-pay1.com/images/1747605237.webp" alt="Slide 2">
        </div>
        <div class="slide">
            <img src="https://1click-pay1.com/images/1747605246.webp" alt="Slide 3">
        </div>
        <div class="slide">
            <img src="https://1click-pay1.com/images/1747605254.webp" alt="Slide 4">
        </div>
        <div class="slide">
            <img src="https://1click-pay1.com/images/1747605259.webp" alt="Slide 5">
        </div>
        <div class="slide-dots">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
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
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1744470524.png" alt="تطبيقات الدردشة">
                <span>تطبيقات الدردشة</span>
            </div>
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1744470549.png" alt="قسم الالعاب">
                <span>قسم الالعاب</span>
            </div>
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1747606468.webp" alt="قسم البطائق">
                <span>قسم البطائق</span>
            </div>
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1747605744.webp" alt="الدفع الاكتروني">
                <span>الدفع الاكتروني</span>
            </div>
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1747605734.webp" alt="قنوات المشاهده">
                <span>قنوات المشاهده</span>
            </div>
            <div class="category-item">
                <img src="https://1click-pay1.com/images/1747606461.webp" alt="التواصل الاجتماعي">
                <span>التواصل الاجتماعي</span>
            </div>
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
