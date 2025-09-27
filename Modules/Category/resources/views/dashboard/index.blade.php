@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Categories'))

@push('styles')
    <style>
        /* Dark mode for table */
        /* Dark mode override for Bootstrap tables */
        body[data-theme="dark"] table.dataTable,
        body[data-theme="dark"] table.dataTable th,
        body[data-theme="dark"] table.dataTable td {
            background-color: #2c2c2e !important;
            color: #e5e5e5 !important;
        }

        /* Header */
        body[data-theme="dark"] table.dataTable thead th {
            background-color: #3a3a3c !important;
            color: #e5e5e5 !important;
        }

        /* Rows */
        body[data-theme="dark"] table.dataTable tbody tr:nth-child(odd) {
            background-color: #2c2c2e !important;
        }

        body[data-theme="dark"] table.dataTable tbody tr:nth-child(even) {
            background-color: #323234 !important;
        }

        body[data-theme="dark"] table.dataTable tbody tr:hover {
            background-color: #444446 !important;
        }

        /* Pagination */
        body[data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #3a3a3c !important;
            color: #e5e5e5 !important;
            border: none !important;
        }

        body[data-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0d6efd !important;
            color: #fff !important;
        }

        /* Search box */
        body[data-theme="dark"] .dataTables_wrapper .dataTables_filter input {
            background-color: #3a3a3c !important;
            color: #e5e5e5 !important;
            border: 1px solid #555 !important;
        }
    </style>
@endpush




@section('content')
    <div class="container mt-4 mb-4">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('dashboard.category.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>
                    {{ __('Add New Category') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            {{ __('Categories List') }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <table id="categoriesTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Subcategories') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @php
                                                $media = $category->getFirstMedia('category_images');
                                            @endphp
                                            @if ($media)
                                                <img src="{{ route('category.image', $media->id) }}"
                                                    alt="{{ $category->name }}" width="50">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->children->count() > 0)
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#subcats-{{ $category->id }}" aria-expanded="false"
                                                    aria-controls="subcats-{{ $category->id }}">
                                                    {{ __('Show :count Subcategories', ['count' => $category->children->count()]) }}
                                                </button>
                                                <div class="collapse mt-2" id="subcats-{{ $category->id }}">
                                                    <ul style="padding-right: 15px; margin: 0; list-style-type: disc;">
                                                        @foreach ($category->children as $child)
                                                            <li>{{ $child->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('No Subcategories') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.category.edit', $category->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Subcategories') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
        let table = $('#categoriesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            order: [[0, 'asc']],
            pageLength: 10
        });

        // تحديث عند تغيير الثيم
        function updateTableTheme() {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            if (isDark) {
                $('#categoriesTable').addClass('table-dark-mode');
            } else {
                $('#categoriesTable').removeClass('table-dark-mode');
            }
        }

        // راقب التغيير على data-theme
        const observer = new MutationObserver(updateTableTheme);
        observer.observe(document.body, { attributes: true, attributeFilter: ['data-theme'] });

        // شغّل أول مرة
        updateTableTheme();
    });
</script>

@endpush
