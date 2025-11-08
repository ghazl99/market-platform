@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Edit Product'))

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/product_create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Force remove any duplicate background images first for selects */
        select.form-select,
        .form-select,
        select.form-input {
            background-image: none !important;
        }

        /* Responsive Theme - Light by Default */
        body {
            background: var(--light-bg, #f8fafc);
            color: var(--text-primary, #1f2937);
        }

        .product-edit-container {
            background: var(--light-bg, #f8fafc);
            min-height: 100vh;
            padding: 2rem;
        }

        /* Dark Theme Support */
        [data-theme="dark"] body {
            background: #1a1a1a;
            color: #ffffff;
        }

        [data-theme="dark"] .product-edit-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Product Header Section */
        .product-header {
            background: var(--card-bg, #ffffff);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color, #e5e7eb);
        }

        [data-theme="dark"] .product-header {
            background: #2d2d2d;
            border: 1px solid #404040;
        }

        .product-title-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-title-info {
            flex: 1;
        }

        .product-status-info {
            display: flex;
            flex-direction: row;
            gap: 0.75rem;
            align-items: center;
        }

        .status-info {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-info.available {
            background: #10b981;
            color: #ffffff;
        }

        /* Light Theme Support */
        [data-theme="light"] .product-status-badge.active,
        :not([data-theme]) .product-status-badge.active {
            background: #10b981;
            color: #ffffff;
        }

        .status-info.api {
            background: #f59e0b;
            color: #000000;
        }

        .product-image-small {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #404040;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin: 0;
        }

        [data-theme="dark"] .product-title {
            color: #ffffff;
        }

        .product-subtitle {
            color: #a0a0a0;
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }

        .product-warning {
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 8px;
            padding: 1rem;
            color: #ffcc00;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }


        /* Form Container */
        .form-container {
            background: #2d2d2d;
            border-radius: 12px;
            border: 1px solid #404040;
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            min-height: 120px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
        }

        .form-select:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-checkbox {
            width: 20px;
            height: 20px;
            accent-color: #f59e0b;
        }

        .btn-primary {
            background: #f59e0b;
            color: #000000;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #d97706;
        }

        .btn-secondary {
            background: #6b7280;
            color: #ffffff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 1rem;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        /* Image Upload */
        .image-upload-area {
            border: 2px dashed #555;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: #3a3a3a;
            transition: all 0.3s ease;
        }

        .image-upload-area:hover {
            border-color: #f59e0b;
            background: #404040;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {}
    </style>

    <style>
        /* تحسين مظهر قائمة الأقسام - تنسيق داكن */
        .form-input {
            background-color: #374151 !important;
            border: 1px solid #4b5563 !important;
            color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 12px center !important;
            background-repeat: no-repeat !important;
            background-size: 16px !important;
            padding-right: 40px !important;
        }

        .form-input:focus {
            background-color: #374151 !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        /* تنسيق خيارات القائمة المنسدلة */
        .form-input option {
            background-color: #374151 !important;
            color: #ffffff !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            border: none !important;
        }

        .form-input option[value=""] {
            color: #9ca3af !important;
            font-style: italic !important;
        }

        .form-input option:hover {
            background-color: #4b5563 !important;
        }

        .form-input option:checked {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
        }

        /* تنسيق الأقسام الرئيسية */
        .form-input option:not([value=""]) {
            color: #ffffff !important;
        }

        /* تنسيق الأقسام الفرعية */
        .form-input option[style*="└─"] {
            color: #d1d5db !important;
            font-weight: 500 !important;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في بعض المتصفحات */
        .form-input::-ms-expand {
            display: none;
        }

        .form-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* أنماط الوضع الداكن للقوائم المنسدلة - Maximum Priority */
        [data-theme="dark"] select.form-input,
        [data-theme="dark"] body select.form-input,
        html[data-theme="dark"] select.form-input,
        html[data-theme="dark"] body select.form-input {
            background-color: #2d2d2d !important;
            border: 1px solid #404040 !important;
            color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        [data-theme="dark"] select.form-input:focus,
        [data-theme="dark"] body select.form-input:focus,
        html[data-theme="dark"] select.form-input:focus,
        html[data-theme="dark"] body select.form-input:focus {
            background-color: #2d2d2d !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        /* أنماط خيارات القائمة المنسدلة في الوضع الداكن */
        [data-theme="dark"] select.form-input option,
        [data-theme="dark"] body select.form-input option,
        html[data-theme="dark"] select.form-input option,
        html[data-theme="dark"] body select.form-input option {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] select.form-input option[value=""],
        [data-theme="dark"] body select.form-input option[value=""],
        html[data-theme="dark"] select.form-input option[value=""],
        html[data-theme="dark"] body select.form-input option[value=""] {
            background-color: #2d2d2d !important;
            color: #9ca3af !important;
        }

        [data-theme="dark"] select.form-input option:hover,
        [data-theme="dark"] body select.form-input option:hover,
        html[data-theme="dark"] select.form-input option:hover,
        html[data-theme="dark"] body select.form-input option:hover {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] select.form-input option:checked,
        [data-theme="dark"] body select.form-input option:checked,
        html[data-theme="dark"] select.form-input option:checked,
        html[data-theme="dark"] body select.form-input option:checked {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] select.form-input option:not([value=""]),
        [data-theme="dark"] body select.form-input option:not([value=""]),
        html[data-theme="dark"] select.form-input option:not([value=""]),
        html[data-theme="dark"] body select.form-input option:not([value=""]) {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] select.form-input option[style*="└─"],
        [data-theme="dark"] body select.form-input option[style*="└─"],
        html[data-theme="dark"] select.form-input option[style*="└─"],
        html[data-theme="dark"] body select.form-input option[style*="└─"] {
            background-color: #2d2d2d !important;
            color: #d1d5db !important;
        }

        /* RTL Support للوضع الداكن */
        [dir="rtl"] [data-theme="dark"] select.form-input,
        [dir="rtl"] html[data-theme="dark"] select.form-input,
        [dir="rtl"] [data-theme="dark"] body select.form-input,
        [dir="rtl"] html[data-theme="dark"] body select.form-input {
            background-position: left 12px center !important;
            padding-right: 1rem !important;
            padding-left: 40px !important;
        }
    </style>
@endpush

@section('content')
    <!-- Professional Notifications -->
    <div class="notification-container">
        @if (session('success'))
            <div class="notification success professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
                    <div class="notification-message">{{ session('success') }}</div>
                    <div class="notification-details">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Product') }} #{{ $product->id }} {{ __('has been updated successfully') }}
                    </div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('error'))
            <div class="notification error professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Error') }}</div>
                    <div class="notification-message">{{ session('error') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('warning'))
            <div class="notification warning professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Warning') }}</div>
                    <div class="notification-message">{{ session('warning') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('info'))
            <div class="notification info professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Info') }}</div>
                    <div class="notification-message">{{ session('info') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if ($errors->any())
            <div class="notification error professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Error') }}</div>
                    <div class="notification-message">{{ __('Please fix the errors below') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif
    </div>

    <!-- Product Header Section -->
    <div class="product-header">
        <div class="product-title-section">
            @if ($product->getFirstMedia('product_images'))
                @php $productHeaderMedia = $product->getFirstMedia('product_images'); @endphp
                <img src="{{ route('dashboard.product.image', $productHeaderMedia->id) }}" alt="{{ $product->name }}"
                    class="product-image-small">
            @else
                <div class="product-image-small"
                    style="background: #3a3a3a; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-box-open" style="font-size: 2rem; color: #a0a0a0;"></i>
                </div>
            @endif
            <div class="product-title-info">
                <h1 class="product-title">{{ $product->getTranslation('name', app()->getLocale()) }}</h1>
                <p class="product-subtitle">{{ $product->getTranslation('name', 'en') }}</p>
            </div>
            <div class="product-status-info">
                <span class="status-info available">{{ __('Available') }}</span>
                <span class="status-info api">{{ __('API') }}</span>
            </div>
        </div>

        <div class="product-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ __('Make sure to enter information correctly') }}
        </div>
    </div>

    <form class="form-container" id="productForm" method="POST"
        action="{{ route('dashboard.product.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="form-section">
            <h3 class="section-title">{{ __('Basic Information') }}</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required">{{ __('Product Name') }}</label>
                    <input type="text" class="form-input @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $product->name) }}" placeholder="{{ __('Enter product name') }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Product name should be clear and distinctive') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label required">{{ __('Price') }}</label>
                    <input type="number" class="form-input @error('price') is-invalid @enderror" name="price"
                        value="{{ old('price', $product->price) }}" placeholder="0.00" step="any" required>
                    @error('price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Enter selling price in USD') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Original Price') }}</label>
                    <input type="number" class="form-input @error('original_price') is-invalid @enderror"
                        name="original_price" value="{{ old('original_price', $product->original_price) }}"
                        placeholder="0.00" step="any">
                    @error('original_price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Original price before discount (optional)') }}</div>
                </div>


                <div class="form-group">
                    <label class="form-label">{{ __('Minimum Quantity') }}</label>
                    <input type="number" class="form-input @error('min_quantity') is-invalid @enderror"
                        name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}" placeholder="1"
                        min="1">
                    @error('min_quantity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Minimum quantity per order') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Maximum Quantity') }}</label>
                    <input type="number" class="form-input @error('max_quantity') is-invalid @enderror"
                        name="max_quantity" value="{{ old('max_quantity', $product->max_quantity) }}" placeholder="100"
                        min="1">
                    @error('max_quantity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Maximum quantity per order') }}</div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="form-section">
            <h3 class="section-title">{{ __('Description') }}</h3>
            <div class="form-group">
                <label class="form-label required">{{ __('Product Description') }}</label>
                <textarea class="form-input @error('description') is-invalid @enderror" name="description" rows="4"
                    placeholder="{{ __('Enter detailed product description') }}" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Provide detailed information about the product') }}</div>
            </div>
        </div>

        <!-- Category -->
        <div class="form-section">
            <h3 class="section-title">الفئة</h3>
            <div class="form-group">
                <label class="form-label required">فئة المنتج</label>
                <select class="form-input @error('category') is-invalid @enderror" name="category" required>
                    <option value="">{{ __('Choose Category') }}</option>
                    @if (isset($categories) && $categories->count() > 0)
                        @foreach ($categories as $category)
                            @if ($category->parent_id)
                                {{-- الأقسام الفرعية --}}
                                <option value="{{ $category->id }}"
                                    {{ old('category', $product->categories->first()?->id) == $category->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;└─ {{ $category->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @else
                                {{-- الأقسام الرئيسية --}}
                                <option value="{{ $category->id }}"
                                    {{ old('category', $product->categories->first()?->id) == $category->id ? 'selected' : '' }}>
                                    📁 {{ $category->getTranslation('name', app()->getLocale()) }}
                                </option>
                                {{-- عرض الأقسام الفرعية تحت القسم الرئيسي --}}
                                @if ($category->children && $category->children->count() > 0)
                                    @foreach ($category->children as $child)
                                        <option value="{{ $child->id }}"
                                            {{ old('category', $product->categories->first()?->id) == $child->id ? 'selected' : '' }}>
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
                <div class="form-help">اختر الفئة الأنسب للمنتج</div>
            </div>
        </div>

        <!-- Image -->
        <div class="form-section">
            <h3 class="section-title">{{ __('Product Images') }}</h3>
            <div class="form-group full-width">
                <label class="form-label">{{ __('Product Images') }}</label>
                <div class="file-upload">
                    <input type="file" class="file-upload-input" id="productImages" name="image" accept="image/*">
                    <label for="productImages" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                        <span class="file-upload-text">{{ __('Drag image here or click to select') }}</span>
                        <span class="file-upload-hint">{{ __('You can upload image (JPG, PNG, GIF, WEBP)') }}</span>
                    </label>
                </div>
                @error('image')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="image-preview" id="imagePreview">
                    @if ($product->getFirstMedia('product_images'))
                        @php $productPreviewMedia = $product->getFirstMedia('product_images'); @endphp
                        <img src="{{ route('dashboard.product.image', $productPreviewMedia->id) }}" alt="Current Product Image"
                            class="preview-image">
                        <div class="image-overlay">
                            <span class="image-text">{{ __('Current Image') }}</span>
                        </div>
                    @endif
                </div>
                <div class="form-help">{{ __('Upload a high-quality product image') }}</div>
            </div>
        </div>

        <!-- Status -->
        <div class="form-section">
            <h3 class="section-title">حالة المنتج</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required">حالة المنتج</label>
                    <select class="form-input @error('status') is-invalid @enderror" name="status" required>
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                            نشط
                        </option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                            غير نشط
                        </option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>
                            مسودة
                        </option>
                    </select>
                    @error('status')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">اختر حالة المنتج</div>
                </div>

                <div class="form-group">
                    <label class="form-label">منتج مميز</label>
                    <select class="form-input @error('is_featured') is-invalid @enderror" name="is_featured">
                        <option value="0" {{ old('is_featured', $product->is_featured) == 0 ? 'selected' : '' }}>
                            لا
                        </option>
                        <option value="1" {{ old('is_featured', $product->is_featured) == 1 ? 'selected' : '' }}>
                            نعم
                        </option>
                    </select>
                    @error('is_featured')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">المنتجات المميزة تظهر بشكل بارز</div>
                </div>
            </div>
        </div>


        <!-- SEO Settings -->
        <div class="form-section">
            <h3 class="section-title">إعدادات SEO</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">عنوان SEO</label>
                    <input type="text" class="form-input @error('seo_title') is-invalid @enderror" name="seo_title"
                        value="{{ old('seo_title', $product->seo_title) }}" placeholder="عنوان SEO لمحركات البحث">
                    @error('seo_title')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">تحسين لمحركات البحث (اختياري)</div>
                </div>

                <div class="form-group">
                    <label class="form-label">وصف SEO</label>
                    <textarea class="form-input @error('seo_description') is-invalid @enderror" name="seo_description" rows="2"
                        placeholder="وصف SEO لمحركات البحث">{{ old('seo_description', $product->seo_description) }}</textarea>
                    @error('seo_description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">وصف مختصر لنتائج البحث</div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions" style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <button type="submit" class="btn-primary" id="updateBtn">
                <i class="fas fa-save"></i>
                {{ __('Update Product') }}
            </button>
            <a href="{{ route('dashboard.product.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    {{-- Do NOT load product_create.js here - it prevents natural form submission --}}

    <script>
        // Product edit page specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Product edit script loaded');

            const form = document.getElementById('productForm');
            const imageInput = document.getElementById('productImages');
            const imagePreview = document.getElementById('imagePreview');

            // Image upload preview with drag & drop support
            if (imageInput && imagePreview) {
                const fileUploadLabel = document.querySelector('.file-upload-label');

                // Handle file input change
                imageInput.addEventListener('change', function(e) {
                    handleFileSelect(e.target.files[0]);
                });

                // Drag and drop support
                if (fileUploadLabel) {
                    // Prevent default drag behaviors
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    // Highlight drop area when item is dragged over it
                    ['dragenter', 'dragover'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, () => {
                            fileUploadLabel.classList.add('dragover');
                        }, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, () => {
                            fileUploadLabel.classList.remove('dragover');
                        }, false);
                    });

                    // Handle dropped files
                    fileUploadLabel.addEventListener('drop', function(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;
                        if (files.length > 0) {
                            imageInput.files = files;
                            handleFileSelect(files[0]);
                        }
                    }, false);
                }

                function handleFileSelect(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.innerHTML = `
                                <div class="preview-item">
                                    <img src="${e.target.result}" class="preview-image" alt="Product Preview">
                                </div>
                            `;
                            imagePreview.style.display = 'grid';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // If no file selected or invalid, show current image if exists
                        @if ($product->getFirstMedia('product_images'))
                            @php $productScriptMedia = $product->getFirstMedia('product_images'); @endphp
                            imagePreview.innerHTML = `
                                <div class="preview-item">
                                    <img src="{{ route('dashboard.product.image', $productScriptMedia->id) }}" class="preview-image" alt="Current Product Image">
                                </div>
                            `;
                            imagePreview.style.display = 'grid';
                        @else
                            imagePreview.innerHTML = '';
                            imagePreview.style.display = 'none';
                        @endif
                    }
                }

                // Initialize preview with current image
                @if ($product->getFirstMedia('product_images'))
                    @php $productInitMedia = $product->getFirstMedia('product_images'); @endphp
                    imagePreview.innerHTML = `
                        <div class="preview-item">
                            <img src="{{ route('dashboard.product.image', $productInitMedia->id) }}" class="preview-image" alt="Current Product Image">
                        </div>
                    `;
                    imagePreview.style.display = 'grid';
                @endif
            }

            // Form submission handling - Add loading state (without preventing default)
            form.addEventListener('submit', function(e) {
                console.log('🔄 Form submitting...');

                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Updating...') }}';
                    submitBtn.disabled = true;

                    // Fallback: re-enable after 30s
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            console.warn('⚠️ Button re-enabled due to timeout');
                        }
                    }, 30000);
                }
            });

            // Real-time validation
            const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.style.borderColor = '#ef4444';
                    } else {
                        this.style.borderColor = '';
                    }
                });
            });

            // Auto-generate SEO title from name
            const nameInput = form.querySelector('input[name="name"]');
            const seoTitleInput = form.querySelector('input[name="seo_title"]');

            if (nameInput && seoTitleInput) {
                nameInput.addEventListener('input', function() {
                    if (!seoTitleInput.value) {
                        seoTitleInput.value = this.value;
                    }
                });
            }

            // Auto-generate SEO description from description
            const descriptionInput = form.querySelector('textarea[name="description"]');
            const seoDescriptionInput = form.querySelector('textarea[name="seo_description"]');

            if (descriptionInput && seoDescriptionInput) {
                descriptionInput.addEventListener('input', function() {
                    if (!seoDescriptionInput.value) {
                        const truncated = this.value.length > 150 ? this.value.substring(0, 150) + '...' :
                            this.value;
                        seoDescriptionInput.value = truncated;
                    }
                });
            }
        });

        // Auto-hide notifications after 6 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.professional-notification');
            notifications.forEach(notification => {
                // Progress bar animation
                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 6s linear';
                }

                // Auto-hide after 6 seconds
                setTimeout(() => {
                    notification.classList.add('hide');
                    setTimeout(() => {
                        notification.remove();
                    }, 400);
                }, 6000);
            });

            console.log('✅ Product Edit Page Loaded');
            console.log('✅ All handlers attached successfully');
        });

        // Notification functions
        function showSuccessNotification(message, action = 'update') {
            const notification = document.createElement('div');
            notification.className = 'notification success professional-notification show';
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            `;

            document.querySelector('.notification-container').appendChild(notification);

            // Show notification with animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Auto-hide after 6 seconds
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }, 6000);

            // Progress bar animation
            const progressBar = notification.querySelector('.notification-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = 'width 6s linear';
            }
        }

        function showErrorNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification error professional-notification show';
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Error') }}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            `;

            document.querySelector('.notification-container').appendChild(notification);

            // Show notification with animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Auto-hide after 6 seconds
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }, 6000);

            // Progress bar animation
            const progressBar = notification.querySelector('.notification-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = 'width 6s linear';
            }
        }
    </script>

    <style>
        /* Professional Notification Styles */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .notification {
            position: relative;
            background: rgba(17, 24, 39, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 15px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 4px solid;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.hide {
            transform: translateX(100%);
            opacity: 0;
        }

        .notification-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .notification-icon i {
            font-size: 24px;
        }

        .notification-content {
            flex: 1;
            color: #ffffff;
        }

        .notification-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .notification-message {
            font-size: 14px;
            line-height: 1.5;
            color: #d1d5db;
            margin-bottom: 8px;
        }

        .notification-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 18px;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .notification-close:hover {
            color: #ffffff;
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            width: 0;
            border-radius: 0 0 12px 12px;
        }

        .professional-notification {
            border-left: 4px solid;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .professional-notification.success {
            border-left-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        }

        .professional-notification.error {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        .professional-notification.warning {
            border-left-color: #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
        }

        .professional-notification.info {
            border-left-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        }

        .notification-details {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 0.85rem;
            color: #cccccc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-details i {
            color: #059669;
            font-size: 0.9rem;
        }

        .professional-notification .notification-icon i {
            font-size: 1.5rem;
            animation: successPulse 0.6s ease-out;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0.8);
                opacity: 0.7;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .professional-notification.show {
            animation: professionalSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes professionalSlideIn {
            0% {
                opacity: 0;
                transform: translateX(100%) scale(0.8);
            }

            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        /* RTL Support */
        [dir="rtl"] .notification-container {
            right: auto;
            left: 20px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .notification-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .notification {
                padding: 15px;
            }
        }

        /* ============================================
                                                   Light Mode Styles - Maximum Priority
                                                   ============================================ */

        /* Container and Main Elements */
        html[data-theme="light"] body,
        html[data-theme="light"] .product-edit-container,
        html[data-theme="light"] body .product-edit-container {
            background: #ffffff !important;
            color: #111827 !important;
        }

        /* Page Header */
        html[data-theme="light"] .page-header,
        html[data-theme="light"] body .page-header {
            background: transparent !important;
            border: none !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .back-btn,
        html[data-theme="light"] body .back-btn {
            background: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .back-btn:hover,
        html[data-theme="light"] body .back-btn:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        /* Product Header */
        html[data-theme="light"] .product-header,
        html[data-theme="light"] body .product-header {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .product-title,
        html[data-theme="light"] body .product-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .product-subtitle,
        html[data-theme="light"] body .product-subtitle {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .product-image-small,
        html[data-theme="light"] body .product-image-small {
            border-color: #e5e7eb !important;
        }

        /* Form Container and Sections */
        html[data-theme="light"] .form-container,
        html[data-theme="light"] .form-section,
        html[data-theme="light"] body .form-container,
        html[data-theme="light"] body .form-section {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .section-title,
        html[data-theme="light"] body .section-title {
            color: #111827 !important;
            border-bottom-color: #059669 !important;
        }

        /* Form Groups */
        html[data-theme="light"] .form-group,
        html[data-theme="light"] body .form-group {
            background: transparent !important;
        }

        /* Form Labels */
        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #374151 !important;
        }

        html[data-theme="light"] .form-label.required::after,
        html[data-theme="light"] body .form-label.required::after {
            color: #ef4444 !important;
        }

        /* Form Inputs */
        html[data-theme="light"] .form-input,
        html[data-theme="light"] body .form-input {
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input:focus,
        html[data-theme="light"] body .form-input:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
        }

        html[data-theme="light"] .form-input::placeholder,
        html[data-theme="light"] body .form-input::placeholder {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .form-input[readonly],
        html[data-theme="light"] body .form-input[readonly] {
            background: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            color: #6b7280 !important;
            cursor: not-allowed !important;
        }

        /* Form Textarea */
        html[data-theme="light"] .form-textarea,
        html[data-theme="light"] body .form-textarea {
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-textarea:focus,
        html[data-theme="light"] body .form-textarea:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
        }

        /* Form Select (Light Mode) - Single Arrow Only */
        html[data-theme="light"] .form-select,
        html[data-theme="light"] select.form-input,
        html[data-theme="light"] body .form-select,
        html[data-theme="light"] body select.form-input {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
        }

        html[data-theme="light"] .form-select:focus,
        html[data-theme="light"] select.form-input:focus,
        html[data-theme="light"] body .form-select:focus,
        html[data-theme="light"] body select.form-input:focus {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
        }

        html[data-theme="light"] .form-select option,
        html[data-theme="light"] select.form-input option,
        html[data-theme="light"] body .form-select option,
        html[data-theme="light"] body select.form-input option {
            background: #ffffff !important;
            background-color: #ffffff !important;
            color: #111827 !important;
        }

        /* Form Help Text */
        html[data-theme="light"] .form-help,
        html[data-theme="light"] body .form-help {
            color: #6b7280 !important;
        }

        /* Form Error Messages */
        html[data-theme="light"] .form-error,
        html[data-theme="light"] body .form-error {
            color: #ef4444 !important;
            background: #fef2f2 !important;
            border-color: #fecaca !important;
        }

        /* File Upload Area - Force White */
        html[data-theme="light"] .file-upload,
        html[data-theme="light"] .file-upload-area,
        html[data-theme="light"] .file-upload-label,
        html[data-theme="light"] body .file-upload,
        html[data-theme="light"] body .file-upload-area,
        html[data-theme="light"] body .file-upload-label {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 2px dashed #e5e7eb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .file-upload:hover,
        html[data-theme="light"] .file-upload-area:hover,
        html[data-theme="light"] .file-upload-label:hover,
        html[data-theme="light"] body .file-upload:hover,
        html[data-theme="light"] body .file-upload-area:hover,
        html[data-theme="light"] body .file-upload-label:hover {
            background: #f9fafb !important;
            background-color: #f9fafb !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .file-upload-icon,
        html[data-theme="light"] .file-upload-text,
        html[data-theme="light"] .file-upload-hint,
        html[data-theme="light"] body .file-upload-icon,
        html[data-theme="light"] body .file-upload-text,
        html[data-theme="light"] body .file-upload-hint {
            color: #6b7280 !important;
        }

        /* Buttons */
        html[data-theme="light"] .btn-primary,
        html[data-theme="light"] .btn-submit,
        html[data-theme="light"] body .btn-primary,
        html[data-theme="light"] body .btn-submit {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .btn-primary:hover,
        html[data-theme="light"] .btn-submit:hover,
        html[data-theme="light"] body .btn-primary:hover,
        html[data-theme="light"] body .btn-submit:hover {
            background: #047857 !important;
            border-color: #047857 !important;
        }

        html[data-theme="light"] .btn-secondary,
        html[data-theme="light"] body .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        html[data-theme="light"] .btn-secondary:hover,
        html[data-theme="light"] body .btn-secondary:hover {
            background: #e5e7eb !important;
            border-color: #d1d5db !important;
        }

        /* Product Warning */
        html[data-theme="light"] .product-warning,
        html[data-theme="light"] body .product-warning {
            background: #fef3c7 !important;
            border-color: #fbbf24 !important;
            color: #92400e !important;
        }

        /* Form Actions Container - Force White */
        html[data-theme="light"] .form-actions,
        html[data-theme="light"] body .form-actions,
        html[data-theme="light"] .product-edit-container .form-actions {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-top: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        /* Ensure buttons inside form-actions keep their colors */
        html[data-theme="light"] .form-actions .btn-primary,
        html[data-theme="light"] .form-actions .btn.btn-primary,
        html[data-theme="light"] .form-actions button.btn-primary,
        html[data-theme="light"] body .form-actions .btn-primary,
        html[data-theme="light"] body .form-actions .btn.btn-primary,
        html[data-theme="light"] body .form-actions button.btn-primary {
            background: #059669 !important;
            background-color: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-actions .btn-secondary,
        html[data-theme="light"] .form-actions .btn.btn-secondary,
        html[data-theme="light"] .form-actions button.btn-secondary,
        html[data-theme="light"] body .form-actions .btn-secondary,
        html[data-theme="light"] body .form-actions .btn.btn-secondary,
        html[data-theme="light"] body .form-actions button.btn-secondary {
            background: #f3f4f6 !important;
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        html[data-theme="light"] .form-actions .btn-primary:hover,
        html[data-theme="light"] .form-actions .btn.btn-primary:hover,
        html[data-theme="light"] .form-actions button.btn-primary:hover,
        html[data-theme="light"] body .form-actions .btn-primary:hover,
        html[data-theme="light"] body .form-actions .btn.btn-primary:hover,
        html[data-theme="light"] body .form-actions button.btn-primary:hover {
            background: #047857 !important;
            background-color: #047857 !important;
            border-color: #047857 !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .form-actions .btn-secondary:hover,
        html[data-theme="light"] .form-actions .btn.btn-secondary:hover,
        html[data-theme="light"] .form-actions button.btn-secondary:hover,
        html[data-theme="light"] body .form-actions .btn-secondary:hover,
        html[data-theme="light"] body .form-actions .btn.btn-secondary:hover,
        html[data-theme="light"] body .form-actions button.btn-secondary:hover {
            background: #e5e7eb !important;
            background-color: #e5e7eb !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }

        /* RTL Support for Light Mode */
        [dir="rtl"] html[data-theme="light"] .form-select,
        [dir="rtl"] html[data-theme="light"] select.form-input,
        [dir="rtl"] html[data-theme="light"] body .form-select,
        [dir="rtl"] html[data-theme="light"] body select.form-input {
            background-position: left 0.5rem center !important;
            padding-right: 1rem !important;
            padding-left: 2.5rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                document.body.offsetHeight;
                document.documentElement.setAttribute('data-theme', 'light');

                // Force apply light theme to all inputs and containers
                const inputs = document.querySelectorAll(
                    '.form-input, .form-select, select.form-input, .form-textarea');
                inputs.forEach(input => {
                    if (input.classList.contains('form-select') || input.tagName === 'SELECT') {
                        // For selects, ensure single arrow and white background
                        input.style.cssText +=
                            'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important; color: #111827 !important; background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%23111827\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'m6 8 4 4 4-4\'/%3e%3c/svg%3e") !important; background-position: right 0.5rem center !important; background-repeat: no-repeat !important; background-size: 1.5em 1.5em !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important;';
                    } else if (input.readOnly) {
                        input.style.cssText +=
                            'background: #f3f4f6 !important; background-color: #f3f4f6 !important; border-color: #e5e7eb !important; color: #6b7280 !important;';
                    } else {
                        input.style.cssText +=
                            'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important; color: #111827 !important;';
                    }
                });

                // Force white background for form containers
                const containers = document.querySelectorAll('.form-container, .form-section');
                containers.forEach(container => {
                    container.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important;';
                });

                // Force white for section titles
                const titles = document.querySelectorAll('.section-title');
                titles.forEach(title => {
                    title.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; color: #111827 !important;';
                });

                // Force white for file upload area
                const fileUploads = document.querySelectorAll(
                    '.file-upload, .file-upload-area, .file-upload-label');
                fileUploads.forEach(upload => {
                    upload.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important;';
                });

                // Force white for form actions container
                const formActions = document.querySelectorAll('.form-actions');
                formActions.forEach(action => {
                    action.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; border-top-color: #e5e7eb !important;';
                });

                // Ensure buttons keep their colors and text is visible
                const primaryButtons = document.querySelectorAll(
                    '.form-actions .btn-primary, .form-actions .btn.btn-primary, .form-actions button.btn-primary'
                );
                primaryButtons.forEach(btn => {
                    btn.style.cssText +=
                        'background: #059669 !important; background-color: #059669 !important; color: #ffffff !important; border-color: #059669 !important;';
                });

                const secondaryButtons = document.querySelectorAll(
                    '.form-actions .btn-secondary, .form-actions .btn.btn-secondary, .form-actions button.btn-secondary'
                );
                secondaryButtons.forEach(btn => {
                    btn.style.cssText +=
                        'background: #f3f4f6 !important; background-color: #f3f4f6 !important; color: #374151 !important; border-color: #e5e7eb !important;';
                });

            }
        });
    </script>
@endpush
