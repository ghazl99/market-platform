@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Add New Product'))

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/product_create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* تحسين مظهر قائمة الأقسام - تنسيق متجاوب */
        .form-select {
            background-color: var(--input-bg, #ffffff) !important;
            border: 1px solid var(--border-color, #e5e7eb) !important;
            color: var(--text-primary, #1f2937) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='var(--text-primary,%236b7280)' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 12px center !important;
            background-repeat: no-repeat !important;
            background-size: 16px !important;
            padding-right: 40px !important;
        }

        .form-select:focus {
            background-color: var(--input-bg, #ffffff) !important;
            border-color: var(--primary-color, #059669) !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='var(--primary-color,%23059669)' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        /* تنسيق خيارات القائمة المنسدلة */
        .form-select option {
            background-color: var(--card-bg, #ffffff) !important;
            color: var(--text-primary, #1f2937) !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            border: none !important;
        }

        .form-select option[value=""] {
            color: var(--text-secondary, #6b7280) !important;
            font-style: italic !important;
        }

        .form-select option:hover {
            background-color: var(--primary-color, #059669) !important;
            color: #ffffff !important;
        }

        .form-select option:checked {
            background-color: var(--primary-color, #059669) !important;
            color: #ffffff !important;
        }

        /* تنسيق الأقسام الرئيسية */
        .form-select option:not([value=""]) {
            color: var(--text-primary, #1f2937) !important;
        }

        /* تنسيق الأقسام الفرعية */
        .form-select option[style*="└─"] {
            color: var(--text-secondary, #6b7280) !important;
            font-weight: 500 !important;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في بعض المتصفحات */
        .form-select::-ms-expand {
            display: none;
        }

        .form-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في صفحة الإنشاء */
        select.form-select {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في المتصفحات المختلفة */
        .form-select {
            min-height: 40px !important;
            height: auto !important;
        }

        /* تحسين مظهر القائمة المنسدلة عند الفتح */
        .form-select:focus {
            outline: none !important;
        }

        /* إصلاح مشكلة عدم ظهور الخيارات */
        .form-select option {
            display: block !important;
            visibility: visible !important;
        }

        /* أنماط الوضع الداكن للقوائم المنسدلة - Maximum Priority */
        [data-theme="dark"] .form-select,
        [data-theme="dark"] body .form-select,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] body .form-select {
            background-color: #2d2d2d !important;
            border: 1px solid #404040 !important;
            color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] body .form-select:focus,
        html[data-theme="dark"] .form-select:focus,
        html[data-theme="dark"] body .form-select:focus {
            background-color: #2d2d2d !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        /* أنماط خيارات القائمة المنسدلة في الوضع الداكن */
        [data-theme="dark"] .form-select option,
        [data-theme="dark"] body .form-select option,
        html[data-theme="dark"] .form-select option,
        html[data-theme="dark"] body .form-select option {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option[value=""],
        [data-theme="dark"] body .form-select option[value=""],
        html[data-theme="dark"] .form-select option[value=""],
        html[data-theme="dark"] body .form-select option[value=""] {
            background-color: #2d2d2d !important;
            color: #9ca3af !important;
        }

        [data-theme="dark"] .form-select option:hover,
        [data-theme="dark"] body .form-select option:hover,
        html[data-theme="dark"] .form-select option:hover,
        html[data-theme="dark"] body .form-select option:hover {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option:checked,
        [data-theme="dark"] body .form-select option:checked,
        html[data-theme="dark"] .form-select option:checked,
        html[data-theme="dark"] body .form-select option:checked {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option:not([value=""]),
        [data-theme="dark"] body .form-select option:not([value=""]),
        html[data-theme="dark"] .form-select option:not([value=""]),
        html[data-theme="dark"] body .form-select option:not([value=""]) {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option[style*="└─"],
        [data-theme="dark"] body .form-select option[style*="└─"],
        html[data-theme="dark"] .form-select option[style*="└─"],
        html[data-theme="dark"] body .form-select option[style*="└─"] {
            background-color: #2d2d2d !important;
            color: #d1d5db !important;
        }

        /* RTL Support للوضع الداكن */
        [dir="rtl"] [data-theme="dark"] .form-select,
        [dir="rtl"] html[data-theme="dark"] .form-select,
        [dir="rtl"] [data-theme="dark"] body .form-select,
        [dir="rtl"] html[data-theme="dark"] body .form-select {
            background-position: left 12px center !important;
            padding-right: 1rem !important;
            padding-left: 40px !important;
        }
    </style>
@endpush

@section('content')
    <div class="product-create-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Add New Product') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.product.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Products') }}
                </a>
            </div>
        </div>

        @if (isset($parentProduct))
            <div class="form-section"
                style="background: #2d2d2d; border: 1px solid #404040; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 class="section-title" style="color: #f59e0b;">
                    <i class="fas fa-layer-group"></i>
                    {{ __('Creating Sub-Product') }}
                </h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
                    @if ($parentProduct->getFirstMedia('product_images'))
                        <img src="{{ $parentProduct->getFirstMedia('product_images')->getUrl() }}"
                            alt="{{ $parentProduct->name }}"
                            style="width: 60px; height: 60px; border-radius: 8px; border: 2px solid #404040; object-fit: cover;">
                    @endif
                    <div>
                        <p style="margin: 0; color: #ffffff; font-weight: 600;">
                            {{ __('Parent Product') }}: {{ $parentProduct->getTranslation('name', app()->getLocale()) }}
                        </p>
                        <p style="margin: 0.25rem 0 0 0; color: #9ca3af; font-size: 0.9rem;">
                            ID: {{ $parentProduct->id }} | ${{ number_format($parentProduct->price, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form class="form-container" id="productForm" method="POST" action="{{ route('dashboard.product.store') }}"
            enctype="multipart/form-data">
            @csrf

            @if (isset($parentProduct))
                <input type="hidden" name="parent_id" value="{{ $parentProduct->id }}">
            @endif

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Basic Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Product Name') }}</label>
                        <input type="text" class="form-input @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" placeholder="{{ __('Enter product name') }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Product name should be clear and distinctive') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">{{ __('Price') }}</label>
                        <input type="number" class="form-input @error('price') is-invalid @enderror" name="price"
                            value="{{ old('price') }}" placeholder="0.00" step="0.01" required>
                        @error('price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Price in Saudi Riyal') }}</div>
                    </div>


                    @if (isset($selectedCategory) && !isset($parentProduct))
                        {{-- إذا كان هناك قسم محدد من صفحة المنتجات الخاصة بقسم --}}
                        <input type="hidden" name="category" value="{{ $selectedCategory->id }}">
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <input type="text" class="form-input"
                                value="{{ $selectedCategory->getTranslation('name', app()->getLocale()) }}" readonly
                                style="background: var(--input-bg, #374151); color: var(--text-secondary, #9ca3af); cursor: not-allowed;">
                            <div class="form-help">{{ __('Category is set from the section you are adding from') }}</div>
                        </div>
                    @elseif (isset($parentProduct))
                        {{-- إذا كان منتج فرعي، نسخ الفئة من المنتج الأب تلقائيًا --}}
                        @php
                            $parentCategoryId = $parentProduct->categories->first()->id ?? '';
                            $parentCategoryName = $parentProduct->categories->first()
                                ? $parentProduct->categories->first()->getTranslation('name', app()->getLocale())
                                : __('No category');
                        @endphp
                        @if ($parentCategoryId)
                            <input type="hidden" name="category" value="{{ $parentCategoryId }}">
                        @endif
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <input type="text" class="form-input" value="{{ $parentCategoryName }}" readonly
                                style="background: var(--input-bg, #374151); color: var(--text-secondary, #9ca3af); cursor: not-allowed;">
                            <div class="form-help">{{ __('Category will be inherited from parent product') }}</div>
                        </div>
                    @else
                        {{-- الحالة العادية: عرض قائمة الفئات للاختيار --}}
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                                <option value="">{{ __('Choose Category') }}</option>
                                @if (isset($categories) && $categories->count() > 0)
                                    @foreach ($categories as $category)
                                        @if ($category->parent_id)
                                            {{-- الأقسام الفرعية --}}
                                            <option value="{{ $category->id }}"
                                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;└─
                                                {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @else
                                            {{-- الأقسام الرئيسية --}}
                                            <option value="{{ $category->id }}"
                                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                                📁 {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                            {{-- عرض الأقسام الفرعية تحت القسم الرئيسي --}}
                                            @if ($category->children && $category->children->count() > 0)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ old('category') == $child->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;└─
                                                        {{ $child->getTranslation('name', app()->getLocale()) }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <option value="" disabled>{{ __('No categories available') }}</option>
                                @endif
                            </select>
                            @error('category')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div class="form-help">{{ __('Select the appropriate category for your product') }}</div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Description and Details') }}</h3>
                <div class="form-group full-width">
                    <label
                        class="form-label {{ !isset($parentProduct) ? 'required' : '' }}">{{ __('Product Description') }}</label>
                    <textarea class="form-input form-textarea @error('description') is-invalid @enderror" name="description"
                        placeholder="{{ __('Enter detailed product description') }}" {{ !isset($parentProduct) ? 'required' : '' }}>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">
                        @if (isset($parentProduct))
                            {{ __('Description is optional for sub-products') }}
                        @else
                            {{ __('Comprehensive product description helps customers understand it better') }}
                        @endif
                    </div>
                </div>

            </div>

            <!-- Images -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Product Images') }}</h3>
                <div class="form-group full-width">
                    <label class="form-label required">{{ __('Product Images') }}</label>
                    <div class="file-upload">
                        <input type="file" class="file-upload-input" id="productImages" name="image"
                            accept="image/*">
                        <label for="productImages" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span class="file-upload-text">{{ __('Drag images here or click to select') }}</span>
                            <span
                                class="file-upload-hint">{{ __('You can upload multiple images (JPG, PNG, GIF)') }}</span>
                        </label>
                    </div>
                    @error('image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="image-preview" id="imagePreview"></div>
                </div>
            </div>

            <!-- SEO -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Search Engine Optimization (SEO)') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('SEO Title') }}</label>
                        <input type="text" class="form-input @error('seo_title') is-invalid @enderror"
                            name="seo_title" value="{{ old('seo_title') }}"
                            placeholder="{{ __('SEO optimized title') }}">
                        @error('seo_title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Title that appears in search results') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('Keywords') }}</label>
                        <input type="text" class="form-input @error('keywords') is-invalid @enderror" name="keywords"
                            value="{{ old('keywords') }}" placeholder="{{ __('keyword1, keyword2, keyword3') }}">
                        @error('keywords')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Separated by commas') }}</div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">{{ __('SEO Description') }}</label>
                    <textarea class="form-input form-textarea @error('seo_description') is-invalid @enderror" name="seo_description"
                        placeholder="{{ __('SEO optimized description') }}">{{ old('seo_description') }}</textarea>
                    @error('seo_description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Status and Settings') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Product Status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}
                            </option>
                        </select>
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('Featured') }}</label>
                        <select class="form-select @error('featured') is-invalid @enderror" name="featured">
                            <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>{{ __('Normal') }}
                            </option>
                            <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>{{ __('Featured') }}
                            </option>
                        </select>
                        @error('featured')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary"
                    onclick="window.location.href='{{ route('dashboard.product.index') }}'">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-secondary" id="saveDraftBtn">
                    <i class="fas fa-save"></i>
                    {{ __('Save as Draft') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Product') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script>
        // Wait for notifications to load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Notifications script loaded');
            if (typeof showSuccess === 'function') {
                console.log('showSuccess function is available');
            } else {
                console.log('showSuccess function is NOT available');
                // Create fallback functions
                window.showSuccess = function(title, message) {
                    console.log('Fallback showSuccess:', title, message);
                    alert(title + ': ' + message);
                };
                window.showError = function(title, message) {
                    console.log('Fallback showError:', title, message);
                    alert(title + ': ' + message);
                };
            }
        });
    </script>
    <script src="{{ asset('modules/product/product_create_simple_fix.js') }}"></script>
    <script>
        // Handle Laravel session messages
        @if (session('success'))
            showSuccess('نجح!', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showError('خطأ!', '{{ session('error') }}');
        @endif

        @if (session('warning'))
            showWarning('تحذير!', '{{ session('warning') }}');
        @endif

        @if (session('info'))
            showInfo('معلومة', '{{ session('info') }}');
        @endif

        // Handle validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showError('خطأ في التحقق', '{{ $error }}');
            @endforeach
        @endif

        // التحقق من وجود الأقسام وإصلاح مشاكل القائمة المنسدلة
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.querySelector('select[name="category"]');

            if (categorySelect) {
                console.log('Category select found:', categorySelect);
                console.log('Number of options:', categorySelect.options.length);

                // إصلاح مشكلة عدم ظهور القائمة
                categorySelect.style.display = 'block';
                categorySelect.style.visibility = 'visible';
                categorySelect.style.opacity = '1';

                // إضافة event listener للتأكد من عمل القائمة
                categorySelect.addEventListener('change', function() {
                    console.log('Category changed to:', this.value);
                });

                // إصلاح مشكلة عدم ظهور الخيارات في بعض المتصفحات
                categorySelect.addEventListener('click', function() {
                    this.style.zIndex = '9999';
                });

                categorySelect.addEventListener('blur', function() {
                    this.style.zIndex = 'auto';
                });
            } else {
                console.error('Category select not found!');
            }
        });
    </script>
@endpush
