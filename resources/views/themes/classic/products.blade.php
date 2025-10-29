@extends('themes.app')

@section('title', __('Products - ') . $category->name)

@push('styles')
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
