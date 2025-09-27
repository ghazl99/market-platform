
@extends('core::dashboard.layouts.app')

@section('title', __('Products List'))

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('Products List') }}</h4>
            <a href="{{ route('dashboard.product.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> {{ __('Add New Product') }}
            </a>
        </div>
        <div class="d-flex mb-3">
            <input type="text" id="products-search" class="form-control" placeholder="{{ __('Search products...') }}">
        </div>
        <br>
        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Categories') }}</th>
                            <th>{{ __('Attributes') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-wrapper">
                        @include('product::dashboard.dataTables', ['products' => $products])

                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Categories') }}</th>
                            <th>{{ __('Attributes') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

        <div class="d-flex justify-content-center mt-4" id="products-pagination">
            @if ($products->hasPages())
                {{ $products->links() }}
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <!-- jQuery أولاً -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-product-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    const productName = this.dataset.productName;

                    Swal.fire({
                        title: "{{ __('Are you sure?') }}",
                        text: `{{ __('Do you really want to delete') }} ${productName} ?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __('Yes, delete it!') }}",
                        cancelButtonText: "{{ __('Cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let searchTimeout = null;

            $('#products-search').on('keyup', function() {
                clearTimeout(searchTimeout);
                let keyword = $(this).val();
                searchTimeout = setTimeout(() => fetchProducts(keyword), 300);
            });

            $(document).on('click', '#products-pagination .pagination a', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                const keyword = $('#products-search').val();
                fetchProducts(keyword, pageUrl);
            });

            function fetchProducts(keyword, url = "{{ route('dashboard.product.index') }}") {
                const finalUrl = new URL(url, window.location.origin);
                finalUrl.searchParams.set('search', keyword);

                $.ajax({
                    url: finalUrl.toString(),
                    type: "GET",
                    success: function(response) {
                        $('#products-table-wrapper').html(response.html);

                        if (response.hasPages) {
                            $('#products-pagination').html(response.pagination).show();
                        } else {
                            $('#products-pagination').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $('#products-table-wrapper').html(
                            `<div class="alert alert-danger text-center">خطأ أثناء تحميل المنتجات.</div>`
                        );
                        $('#products-pagination').empty().hide();
                    }
                });
            }
        });
    </script>
@endpush

