@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Add New Product'))

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
@endpush

@section('content')
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>{{ __('Add New Product') }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('dashboard.product.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- اسم المنتج -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Product Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="{{ __('Enter product name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- الوصف -->
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="{{ __('Description') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Product Image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- السعر الأصلي -->
                            <div class="mb-3">
                                <label for="original_price" class="form-label">{{ __('Original Price') }}</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('original_price') is-invalid @enderror" id="original_price"
                                    name="original_price" value="{{ old('original_price') }}"
                                    placeholder="{{ __('Original Price') }}">
                                @error('original_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- السعر -->
                            <div class="mb-3">
                                <label for="price" class="form-label">{{ __('Price') }}</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                    value="{{ old('price') }}" required placeholder="{{ __('Price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- المخزون -->


                            <!-- الأقسام -->
                            <div class="mb-3">
                                <label for="categories" class="form-label">{{ __('Categories') }}</label>
                                <select name="categories[]" id="categories" class="form-control" multiple="multiple">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('categories.*')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- الخصائص -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('Attributes') }}</label>
                                <div id="attributes-container">
                                    <!-- الخصائص الموجودة -->
                                    <div class="mb-2 attribute-item">
                                        <select name="names[]" class="form-control attribute-select"
                                            data-placeholder="{{ __('Select or type new attribute') }}">
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->name }}">{{ $attribute->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="value[]" class="form-control mt-1"
                                            placeholder="{{ __('Attribute Value') }}">
                                        <input type="text" name="unit[]" class="form-control mt-1"
                                            placeholder="{{ __('Unit (optional)') }}">
                                    </div>
                                </div>
                                @error('names.*')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                                @error('value.*')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                                @error('unit.*')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                                <button type="button" id="add-attribute-btn"
                                    class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fas fa-plus me-1"></i>{{ __('Add Attribute') }}
                                </button>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-lg"><i
                                        class="fas fa-save me-2"></i>{{ __('Save') }}</button>
                                <a href="{{ route('dashboard.product.index') }}"
                                    class="btn btn-outline-secondary btn-lg"><i
                                        class="fas fa-arrow-left me-2"></i>{{ __('Back') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categories').select2({
                theme: 'bootstrap-5',
                placeholder: "{{ __('Select categories') }}",
                allowClear: false
            });

            // خصائص موجودة + فارغة
            $('.attribute-select').select2({
                theme: 'bootstrap-5',
                tags: true,
                placeholder: "{{ __('Select attributes') }}",

                tokenSeparators: [',', '،'],

                allowClear: false
            });

            // إضافة حقل جديد ديناميكي
            $('#add-attribute-btn').click(function() {
                // نسخ جميع الخيارات الحالية من أول select
                let options = '';
                $('#attributes-container .attribute-item:first .attribute-select option').each(function() {
                    options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                });

                let html = `
                <div class="mb-2 attribute-item row g-2 align-items-center">
                    <div class="col">
                        <select name="names[]" class="form-control attribute-select" data-placeholder="{{ __('Type attribute name') }}">
                            ${options}
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" name="value[]" class="form-control" placeholder="{{ __('Attribute Value') }}">
                    </div>
                    <div class="col">
                        <input type="text" name="unit[]" class="form-control" placeholder="{{ __('Unit (optional)') }}">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger btn-sm remove-attribute">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>`;

                $('#attributes-container').append(html);

                // تفعيل Select2 للحقل الجديد
                $('#attributes-container .attribute-item:last .attribute-select').select2({
                    theme: 'bootstrap-5',
                    tags: true,
                    tokenSeparators: [',', '،'],
                    placeholder: "{{ __('Select attributes') }}",
                    allowClear: false
                });
            });



            $(document).on('click', '.remove-attribute', function() {
                $(this).closest('.attribute-item').remove();
            });
        });
    </script>
@endpush
