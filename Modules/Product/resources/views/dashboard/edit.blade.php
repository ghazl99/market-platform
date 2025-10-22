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
        /* Dark Theme Product Edit Page - Same as Show Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .product-edit-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Product Header Section */
        .product-header {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
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
            color: #ffffff;
            margin: 0;
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

        /* Navigation Tabs */
        .product-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #404040;
        }

        .tab-item {
            padding: 1rem 2rem;
            background: transparent;
            border: none;
            color: #a0a0a0;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-item.active {
            color: #ffffff;
            border-bottom-color: #f59e0b;
        }

        .tab-item:hover {
            color: #ffffff;
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
        @media (max-width: 768px) {
            .product-tabs {
                flex-wrap: wrap;
            }

            .tab-item {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
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
                <img src="{{ $product->getFirstMedia('product_images')->getUrl() }}" alt="{{ $product->name }}"
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

    <!-- Navigation Tabs -->
    <div class="product-tabs">
        <button class="tab-item">{{ __('Product Overview') }}</button>
        <button class="tab-item active">{{ __('Product Settings') }}</button>
        <button class="tab-item">{{ __('Custom Prices') }}</button>
        <button class="tab-item">{{ __('Inventory') }}</button>
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
                        value="{{ old('price', $product->price) }}" placeholder="0.00" step="0.01" required>
                    @error('price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Enter selling price in SAR') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Original Price') }}</label>
                    <input type="number" class="form-input @error('original_price') is-invalid @enderror"
                        name="original_price" value="{{ old('original_price', $product->original_price) }}"
                        placeholder="0.00" step="0.01">
                    @error('original_price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Original price before discount (optional)') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label required">{{ __('Stock Quantity') }}</label>
                    <input type="number" class="form-input @error('stock_quantity') is-invalid @enderror"
                        name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        placeholder="0" min="0" required>
                    @error('stock_quantity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('Available quantity in stock') }}</div>
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
            <h3 class="section-title">صورة المنتج</h3>
            <div class="form-group">
                <label class="form-label">رفع صورة</label>
                <div class="image-upload-container">
                    <input type="file" class="form-input @error('image') is-invalid @enderror" id="productImages"
                        name="image" accept="image/*">
                    @error('image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="image-preview" id="imagePreview">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="Current Product Image" class="preview-image">
                            <div class="image-overlay">
                                <span class="image-text">الصورة الحالية</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-help">ارفع صورة عالية الجودة للمنتج (JPG, PNG, GIF)</div>
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
    <script src="{{ asset('modules/product/js/product_create.js') }}"></script>

    <script>
        // Product edit page specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Product edit script loaded');

            const form = document.getElementById('productForm');
            const imageInput = document.getElementById('productImages');
            const imagePreview = document.getElementById('imagePreview');

            // Image upload preview
            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.innerHTML = `
                                <img src="${e.target.result}" class="preview-image" alt="Product Preview">
                                <div class="image-overlay">
                                    <span class="image-text">معاينة الصورة الجديدة</span>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.innerHTML = ''; // Clear preview if no file or not an image
                    }
                });
            }

            // Form submission handling
            form.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Updating...') }}';
                submitBtn.disabled = true;

                // Allow form to submit naturally to Laravel
                // Don't prevent default - let Laravel handle the form submission
                console.log('Form submitted, Laravel will handle validation and redirect');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
            });

            // Handle update button click
            const updateBtn = document.getElementById('updateBtn');
            if (updateBtn) {
                updateBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Updating...') }}';
                    this.disabled = true;

                    // Submit the form via AJAX
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-HTTP-Method-Override': 'PUT'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // Show success notification
                                showSuccessNotification('{{ __('Product updated successfully') }}',
                                    'update');

                                // Redirect to products index after a short delay
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('dashboard.product.index') }}';
                                }, 1500);
                            } else {
                                throw new Error('Update failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Restore button state
                            this.innerHTML = originalText;
                            this.disabled = false;
                            showErrorNotification(
                                '{{ __('An error occurred while updating the product') }}');
                        });
                });
            }

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
    </style>
@endpush
