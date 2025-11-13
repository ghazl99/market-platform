@extends('digital.themes.app')

@push('styles')
    <style>
        /* === Light Mode Styles === */
        html[data-theme="light"] body,
        html[data-theme="light"] .main-content-adjust,
        html[data-theme="light"] .products-section {
            background: #ffffff !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .products-grid .product-card,
        html[data-theme="light"] .products-grid .product-card-v2 {
            background: #ffffff !important;
            border: 2px solid #F1F1F4 !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .product-card:hover,
        html[data-theme="light"] .product-card-v2:hover {
            border-color: #ff6f1e !important;
            box-shadow: 0 10px 30px rgba(255, 111, 30, 0.2) !important;
        }

        html[data-theme="light"] .product-price {
            color: #ff6f1e !important;
        }
    </style>
@endpush

@section('content')
    <main class="main-content-adjust">
        <section class="products-section">
            <div class="container">
                <!-- Products Toolbar -->
                <div class="products-toolbar">
                    <div class="products-info">
                        <h2>{{ __('Products') }}</h2>
                    </div>
                    <div class="products-controls">
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="my-3">
                    <input type="text" id="categorySearchInput" class="form-control"
                        placeholder="{{ __('Search products...') }}">
                </div>
                <br>
                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @include('digital.themes.' . current_theme_name_en() . '._products', ['products' => $products])
                </div>

                <!-- No pagination -->
                <div id="paginationLinks"></div>
            </div>
        </section>
    </main>
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
