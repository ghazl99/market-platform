@extends('digital.themes.app')

@section('title', __('Home'))

@push('styles')
    <style>
        /* شبكة الكاردات */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 كاردات في الصف */
            gap: 1.5rem;
            /* المسافة بين الكاردات */
        }

        /* كارد الخدمة */
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

        .category-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .service-content {
            padding: 0.5rem 1rem 1rem;
            text-align: center;
        }

        /* SVG افتراضي */
        .default-store-svg svg {
            width: 100%;
            height: 100%;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <main class="main-content-adjust">

        <section class="services-section">
            <div class="container">
                <div class="services-header mb-4">
                    <h2 class="section-title">{{ __('Subcategories for') }}{{ $category->name }}</h2>
                </div>

                <div class="services-grid">
                    @if ($category->children->count() > 0)
                        @foreach ($category->children as $subCategory)
                            <a href="{{ route('category.show', $subCategory->id) }}" class="service-card">
                                @php $media = $subCategory->getFirstMedia('subcategory_images'); @endphp
                                <div class="service-image">
                                    @if ($media)
                                        <img src="{{ route('category.image', $media->id) }}" alt="{{ $subCategory->name }}"
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
                                                <rect x="6" y="20" width="52" height="36" fill="#e9ecef"
                                                    rx="2" stroke="#adb5bd" stroke-width="1" />
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
                                    <h3>{{ $subCategory->name }}</h3>
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
    </main>
@endsection
