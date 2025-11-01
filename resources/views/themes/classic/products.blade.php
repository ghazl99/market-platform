@extends('themes.app')

@section('title', __('Products - ') . $category->name)

@push('styles')
    <style>
        /* Light Mode Styles for Products Page - Maximum Priority */
        html[data-theme="light"] body,
        html[data-theme="light"] .main-content-adjust,
        html[data-theme="light"] .products-section,
        html[data-theme="light"] body .products-section {
            background: #ffffff !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .products-toolbar,
        html[data-theme="light"] body .products-toolbar {
            background: #ffffff !important;
            border: 2px solid #F1F1F4 !important;
        }

        html[data-theme="light"] .products-info h2,
        html[data-theme="light"] body .products-info h2 {
            color: #252f4a !important;
        }

        html[data-theme="light"] .products-count,
        html[data-theme="light"] body .products-count {
            color: #66718e !important;
        }

        html[data-theme="light"] .filter-btn,
        html[data-theme="light"] body .filter-btn {
            background: #fcfcfc !important;
            border: 2px solid #F1F1F4 !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .filter-btn:hover,
        html[data-theme="light"] body .filter-btn:hover {
            background: linear-gradient(135deg, #ff6f1e, #ff8533) !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .view-toggle,
        html[data-theme="light"] body .view-toggle {
            background: #fcfcfc !important;
            border: 2px solid #F1F1F4 !important;
        }

        html[data-theme="light"] .view-btn,
        html[data-theme="light"] body .view-btn {
            color: #66718e !important;
        }

        html[data-theme="light"] .view-btn.active,
        html[data-theme="light"] .view-btn:hover,
        html[data-theme="light"] body .view-btn.active,
        html[data-theme="light"] body .view-btn:hover {
            background: #ff6f1e !important;
            color: #ffffff !important;
        }

        /* Product Cards - Maximum Priority */
        html[data-theme="light"] .product-card,
        html[data-theme="light"] .product-card-v2,
        html[data-theme="light"] body .product-card,
        html[data-theme="light"] body .product-card-v2,
        html[data-theme="light"] .products-grid .product-card,
        html[data-theme="light"] .products-grid .product-card-v2,
        html[data-theme="light"] body .products-grid .product-card,
        html[data-theme="light"] body .products-grid .product-card-v2 {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 2px solid #F1F1F4 !important;
            border-color: #F1F1F4 !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .product-card:hover,
        html[data-theme="light"] .product-card-v2:hover,
        html[data-theme="light"] body .product-card:hover,
        html[data-theme="light"] body .product-card-v2:hover,
        html[data-theme="light"] .products-grid .product-card:hover,
        html[data-theme="light"] .products-grid .product-card-v2:hover {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-color: #ff6f1e !important;
            box-shadow: 0 10px 30px rgba(255, 111, 30, 0.2) !important;
        }

        html[data-theme="light"] .product-name,
        html[data-theme="light"] body .product-name,
        html[data-theme="light"] .product-card h3,
        html[data-theme="light"] .product-card-v2 h3,
        html[data-theme="light"] body .product-card h3,
        html[data-theme="light"] body .product-card-v2 h3 {
            color: #252f4a !important;
        }

        html[data-theme="light"] .product-info,
        html[data-theme="light"] body .product-info {
            background: transparent !important;
            background-color: transparent !important;
        }

        html[data-theme="light"] .product-price,
        html[data-theme="light"] body .product-price {
            color: #ff6f1e !important;
        }

        /* Ensure all text and icons in product cards are visible */
        html[data-theme="light"] .product-card *,
        html[data-theme="light"] .product-card-v2 *,
        html[data-theme="light"] body .product-card *,
        html[data-theme="light"] body .product-card-v2 * {
            color: inherit !important;
        }

        html[data-theme="light"] .product-card i,
        html[data-theme="light"] .product-card-v2 i,
        html[data-theme="light"] .product-card svg,
        html[data-theme="light"] .product-card-v2 svg,
        html[data-theme="light"] body .product-card i,
        html[data-theme="light"] body .product-card-v2 i,
        html[data-theme="light"] body .product-card svg,
        html[data-theme="light"] body .product-card-v2 svg {
            color: #252f4a !important;
            fill: #252f4a !important;
        }

        /* Product image background in light mode */
        html[data-theme="light"] .product-image,
        html[data-theme="light"] body .product-image {
            background: #fcfcfc !important;
            background-color: #fcfcfc !important;
        }

        /* Override any dark mode styles with higher specificity */
        html[data-theme="light"] .products-grid a.product-card-v2,
        html[data-theme="light"] html[data-theme="light"] body .products-grid a.product-card-v2 {
            background: #ffffff !important;
            background-color: #ffffff !important;
        }

        /* Product badges and icons */
        html[data-theme="light"] .product-badge,
        html[data-theme="light"] body .product-badge {
            background: linear-gradient(135deg, #ff6f1e, #ff8533) !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .btn-icon,
        html[data-theme="light"] body .btn-icon {
            background: #ffffff !important;
            color: #252f4a !important;
            border: 1px solid #F1F1F4 !important;
        }

        html[data-theme="light"] .btn-icon:hover,
        html[data-theme="light"] body .btn-icon:hover {
            background: linear-gradient(135deg, #ff6f1e, #ff8533) !important;
            color: #ffffff !important;
        }

        /* Product overlay */
        html[data-theme="light"] .product-overlay,
        html[data-theme="light"] body .product-overlay {
            background: rgba(255, 255, 255, 0.9) !important;
        }

        html[data-theme="light"] .pagination .page-link,
        html[data-theme="light"] body .pagination .page-link {
            background: #ffffff !important;
            border-color: #F1F1F4 !important;
            color: #252f4a !important;
        }

        html[data-theme="light"] .pagination .page-link:hover,
        html[data-theme="light"] .pagination .page-item.active .page-link,
        html[data-theme="light"] body .pagination .page-link:hover,
        html[data-theme="light"] body .pagination .page-item.active .page-link {
            background: #ff6f1e !important;
            border-color: #ff6f1e !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .alert-info,
        html[data-theme="light"] body .alert-info {
            background: #e7f3ff !important;
            border-color: #b3d9ff !important;
            color: #0066cc !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force apply light theme styles
            function applyLightTheme() {
                const theme = document.documentElement.getAttribute('data-theme');
                if (theme === 'light') {
                    // Force reflow
                    document.body.offsetHeight;

                    // Apply to all product cards using direct style
                    const productCards = document.querySelectorAll('.product-card, .product-card-v2');
                    productCards.forEach(card => {
                        card.style.cssText +=
                            'background: #ffffff !important; background-color: #ffffff !important; border-color: #F1F1F4 !important; color: #252f4a !important;';
                    });

                    // Apply to product names
                    const productNames = document.querySelectorAll(
                        '.product-name, .product-card h3, .product-card-v2 h3');
                    productNames.forEach(name => {
                        name.style.cssText += 'color: #252f4a !important;';
                    });

                    // Apply to icons
                    const icons = document.querySelectorAll('.product-card i, .product-card-v2 i');
                    icons.forEach(icon => {
                        icon.style.cssText += 'color: #252f4a !important;';
                    });

                    // Apply to SVG icons
                    const svgIcons = document.querySelectorAll('.product-card svg, .product-card-v2 svg');
                    svgIcons.forEach(svg => {
                        svg.style.cssText += 'color: #252f4a !important; fill: #252f4a !important;';
                    });
                }
            }

            // Apply on load
            applyLightTheme();

            // Watch for theme changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                        setTimeout(applyLightTheme, 100);
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-theme']
            });
        });
    </script>
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

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @include('themes.' . current_theme_name_en() . '._products', ['products' => $products])
                </div>
                <div class="d-flex justify-content-center mt-4" id="paginationLinks">
                    @if ($products->hasPages())
                        {{ $products->links() }}
                    @endif
                </div>
                <!-- Pagination -->
                {{-- <div class="pagination">
                <button class="page-btn">السابق</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">التالي</button>
            </div> --}}
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            function fetchProducts(url = null, query = '') {
                console.log('Fetching products for query:', query); // Debug
                let baseUrl = "{{ route('category.show', $category->id) }}";
                let requestUrl = url ? url : baseUrl;
                if (query) {
                    requestUrl += requestUrl.includes('?') ? '&query=' + encodeURIComponent(query) : '?query=' +
                        encodeURIComponent(query);
                }
                console.log(baseUrl);

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#productsGrid').html(response.html);
                        if (response.hasPages) {
                            $('#paginationLinks').html(response.pagination).show();
                        } else {
                            $('#paginationLinks').empty().hide();
                        }
                    },
                    error: function(xhr) {
                        console.log('AJAX error:', xhr);
                        $('#productsGrid').html(
                            '<div class="alert alert-danger w-100 text-center">{{ __('Error loading products.') }}</div>'
                        );
                        $('#paginationLinks').empty().hide();
                    }
                });
            }

            // Event delegation: يعمل حتى لو تم تحميل input لاحقاً
            $(document).on('keyup', '#categorySearchInput', function(e) {
                const query = $(this).val();
                fetchProducts(null, query);
            });

            $(document).on('keypress', '#categorySearchInput', function(e) {
                if (e.which == 13) { // Enter
                    e.preventDefault();
                    const query = $(this).val();
                    fetchProducts(null, query);
                }
            });

            // Pagination
            $(document).on('click', '#paginationLinks .pagination a', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                const query = $('#categorySearchInput').val();
                fetchProducts(pageUrl, query);
            });

        });
    </script>
@endpush
