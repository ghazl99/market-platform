@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Categories'))

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
@endpush

@section('content')
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            {{ __('Add New Category') }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('dashboard.category.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- اسم الصنف -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Category Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="{{ __('Enter category name') }}" required autofocus>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- صورة الصنف -->
                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Category Image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- الأقسام الفرعية -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('Subcategories') }}</label>
                                <div id="subcategories-wrapper">
                                    <div class="subcategory-item d-flex gap-2 mb-2">
                                        <input type="text" name="subcategory_name[]" class="form-control"
                                            placeholder="{{ __('Enter subcategory name') }}">
                                        <input type="file" name="subcategory_image[]" class="form-control"
                                            accept="image/*">
                                        <button type="button" class="btn btn-danger remove-subcategory">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" id="add-subcategory" class="btn btn-outline-primary mt-2">
                                    <i class="fas fa-plus"></i> {{ __('Add Subcategory') }}
                                </button>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    {{ __('Save') }}
                                </button>
                                <a href="{{ route('dashboard.category.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    {{ __('Back') }}
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#subcategories').select2({
                theme: 'bootstrap-5',
                tags: true,
                tokenSeparators: [',', '،']
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let index = 1;

            $('#add-subcategory').on('click', function() {
                $('#subcategories-wrapper').append(`
                <div class="subcategory-item d-flex gap-2 mb-2">
                    <input type="text" name="subcategory_name[]" class="form-control"
                        placeholder="{{ __('Enter subcategory name') }}">
                    <input type="file" name="subcategory_image[]" class="form-control" accept="image/*">
                    <button type="button" class="btn btn-danger remove-subcategory">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `);
                index++;
            });

            $(document).on('click', '.remove-subcategory', function() {
                $(this).closest('.subcategory-item').remove();
            });
        });
    </script>
@endpush
