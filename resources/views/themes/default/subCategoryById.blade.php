@extends('themes.app')

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

        /* Empty State Styles */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            text-align: center;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 2rem 0;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.75rem;
        }

        .empty-state-message {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .empty-state-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #007bff;
            border: 2px solid #007bff;
        }

        .btn-outline:hover {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-lg {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        @media (max-width: 576px) {
            .empty-state {
                padding: 2rem 1rem;
            }

            .empty-state-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }

            .empty-state-title {
                font-size: 1.25rem;
            }

            .empty-state-message {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }

            .empty-state-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
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
                                <h3>{{ $subCategory->name }}</h3>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3 class="empty-state-title">{{ __('No Subcategories Yet') }}</h3>
                        <p class="empty-state-message">{{ __('This category doesn\'t have any subcategories yet') }}</p>

                        <div class="empty-state-actions">
                            <!-- عرض المنتجات مباشرة -->
                            <a href="{{ route('category.show', $category->id) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-bag"></i>
                                <span>{{ __('View Products') }}</span>
                            </a>

                            <!-- زر إضافة قسم فرعي (فقط للمالك) -->
                            @auth
                                @role('owner')
                                    <a href="{{ route('dashboard.category.create', ['parent_id' => $category->id]) }}"
                                        class="btn btn-outline btn-lg">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>{{ __('Add Subcategory') }}</span>
                                    </a>
                                @endrole
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
