@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Edit Category'))

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
                            <i class="fas fa-edit me-2"></i>
                            {{ __('Edit Category') }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('dashboard.category.update', $category->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- اسم الصنف -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Category Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}"
                                    placeholder="{{ __('Category Name') }}" required autofocus>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- صورة الصنف -->
                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @if ($category->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                            width="100">
                                    </div>
                                @endif
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- الأقسام الفرعية -->
                            <div class="mb-3">
                                <label for="subcategories" class="form-label">{{ __('Optional Subcategories') }}</label>
                                <select name="subcategories[]" id="subcategories" class="form-control" multiple="multiple">
                                    @foreach ($category->children as $sub)
                                        <option value="{{ $sub->name }}" selected>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    {{ __('You can type new subcategories and they will be added automatically.') }}
                                </small>
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
@endpush
