@extends('themes.app')

@section('title', __('Home'))
@push('styles')
    <style>
        /* Products Page Specific Styles */
        .products-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .products-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Page Header */
        .products-header {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .products-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            pointer-events: none;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }


        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
            min-height: 320px;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
            pointer-events: none;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: all 0.3s ease;
            border-radius: 20px 20px 0 0;
        }

        .product-card:hover .product-image {
            transform: scale(1.02);
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            line-height: 1.4;
            margin: 0;
            text-align: center;
            word-wrap: break-word;
            max-width: 100%;
            padding: 1.5rem 1rem;
            background: #ffffff;
            border-radius: 0 0 20px 20px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pagination-btn {
            background: #ffffff;
            color: #667eea;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .products-container {
                padding: 0 1.5rem;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .products-container {
                padding: 0 1rem;
            }

            .products-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .page-subtitle {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .product-card {
                min-height: 280px;
            }

            .product-image {
                height: 180px;
            }

            .product-title {
                font-size: 1rem;
                padding: 1rem 0.75rem;
            }

            .pagination {
                margin-top: 1.5rem;
                gap: 0.5rem;
            }

            .pagination-btn {
                padding: 0.6rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .products-container {
                padding: 0 0.75rem;
            }

            .products-header {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .page-title {
                font-size: 1.75rem;
                margin-bottom: 0.5rem;
            }

            .page-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .product-card {
                min-height: 250px;
            }

            .product-image {
                height: 160px;
            }

            .product-title {
                font-size: 0.9rem;
                padding: 0.75rem 0.5rem;
            }

            .pagination {
                margin-top: 1rem;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .pagination-btn {
                padding: 0.5rem 0.6rem;
                font-size: 0.8rem;
                min-width: 40px;
            }
        }
    </style>
@endpush
@section('content')
    <section class="products-section">
        <div class="products-container">
            <!-- Page Header -->
            <div class="products-header">
                <h1 class="page-title">{{ __('Subcategories for') }}{{ $category->name }} </h1>
                <p class="page-subtitle">اكتشف مجموعة واسعة من المنتجات والخدمات الرقمية</p>
            </div>


            <!-- Products Grid -->
            <div class="products-grid">
                @if ($category->children->count() > 0)
                    @foreach ($category->children as $subCategory)
                        @php $media = $subCategory->getFirstMedia('category_images'); @endphp

                        <a href="{{ route('category.show', $subCategory->id) }}" class="product-card"
                            style="text-decoration: none">
                            @if ($media)
                                <img src="{{ route('category.image', $media->id) }}" alt="{{ $subCategory->name }}"
                                    class="product-image">
                            @endif
                            <h3 class="product-title">{{ $subCategory->name }} </h3>
                        </a>
                    @endforeach
                @else
                    <div class="alert alert-info text-center">
                        {{ __('No products found for this category.') }}
                    </div>
                @endif
            </div>

            {{-- <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn">السابق</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">التالي</button>
            </div> --}}
        </div>
    </section>



@endsection
