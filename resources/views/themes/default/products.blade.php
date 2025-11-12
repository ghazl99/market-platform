@extends('themes.app')

@section('title', __('Products - '))

@push('styles')
    <style>
        /* --- Grid & Cards --- */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-content {
            padding: 1rem;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-weight: bold;
            color: #059669;
            margin-bottom: 0.3rem;
        }

        .product-old-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .btn-details {
            margin-top: auto;
            background: #059669;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-details:hover {
            background: #047857;
        }

        .inactive-product {
            filter: grayscale(100%);
            opacity: 0.6;
        }

        .back-btn {
            background: #059669;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #047857;
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .search-box .category-search-input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            /* ترك مسافة يسار للأيقونة */
            border-radius: 25px;
            border: 1px solid #cbd5e1;
            outline: none;
            font-size: 0.95rem;
        }

        .search-box .search-icon {
            position: absolute;
            left: 10px;
            /* إذا RTL ممكن نخليها right */
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }


        @media(max-width:1200px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media(max-width:992px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:480px) {
            .products-grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .product-image {
                height: 120px;
            }
        }

        a {
            text-decoration: none;
            color: #000;

        }

        a:hover {
            text-decoration: none;
            color: #000;
        }

        .product-title {
            text-decoration: none;
            color: #000;

        }
    </style>
@endpush

@section('content')
    <div class="container my-5">

        <h2 class="section-title">{{ $category->name }}</h2>
        <br>
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="categorySearchInput" class="category-search-input" placeholder="">
        </div>

        <br>

        <div class="products-grid" id="productsGrid">
            @include('themes.default._products', ['products' => $products])
        </div>

        <div class="d-flex justify-content-center mt-4" id="paginationLinks">
            @if ($products->hasPages())
                {{ $products->links() }}
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            function fetchProducts(query = '') {
                let baseUrl = "{{ route('category.show', $category->id) }}";
                let requestUrl = query ? baseUrl + '?query=' + encodeURIComponent(query) : baseUrl;

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(response) {
                        // تحديث شبكة المنتجات مباشرة
                        $('#productsGrid').html(response.html);

                        // لا داعي للباجينيشن
                        $('#paginationLinks').empty().hide();
                    },
                    error: function(xhr) {
                        console.error('AJAX error:', xhr);
                        $('#productsGrid').html(
                            '<div class="alert alert-danger w-100 text-center">{{ __('Error loading products.') }}</div>'
                        );
                        $('#paginationLinks').empty().hide();
                    }
                });
            }

            // البحث عند الكتابة
            $(document).on('keyup', '#categorySearchInput', function() {
                fetchProducts($(this).val());
            });

            // البحث عند الضغط على Enter
            $(document).on('keypress', '#categorySearchInput', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    fetchProducts($(this).val());
                }
            });

        });
    </script>
@endpush
