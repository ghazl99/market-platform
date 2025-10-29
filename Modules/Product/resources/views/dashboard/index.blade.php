@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Products'))

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

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

        /* Breadcrumb Navigation Styles */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .breadcrumb-link {
            color: #60a5fa;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .breadcrumb-link:hover {
            background: rgba(96, 165, 250, 0.1);
            color: #93c5fd;
            transform: translateY(-1px);
        }

        .breadcrumb-separator {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .breadcrumb-current {
            color: #ffffff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .back-btn {
            background: #6b7280;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .back-btn:hover {
            background: #4b5563;
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Products Grid - 6 items per row */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.5rem;
            padding: 2rem 0;
        }

        .product-card {
            background: #1f2937;
            border-radius: 16px;
            padding: 1rem;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 1px solid #374151;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .product-card:hover::before {
            opacity: 1;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            border-color: #4b5563;
        }

        .product-card:hover .product-logo {
            color: #ffffff;
            transform: scale(1.1);
        }

        .product-card:hover .menu-toggle {
            background: rgba(156, 163, 175, 0.4);
            color: #ffffff;
            border-color: rgba(156, 163, 175, 0.6);
            transform: scale(1.1);
        }

        /* Product Logo */
        .product-logo {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            color: #9ca3af;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        /* Three Dots Menu */
        .product-menu {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            z-index: 10;
        }

        .product-menu * {
            pointer-events: auto;
        }

        .menu-toggle {
            background: rgba(55, 65, 81, 0.5);
            border: 1px solid rgba(156, 163, 175, 0.3);
            color: #9ca3af;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            position: relative;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-toggle:hover {
            background: rgba(156, 163, 175, 0.3);
            color: #ffffff;
            border-color: rgba(156, 163, 175, 0.5);
            transform: scale(1.05);
        }

        .menu-toggle:active {
            transform: scale(0.95);
        }

        .menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #1f2937;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            min-width: 150px;
            z-index: 1000;
            display: none;
            border: 1px solid #374151;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .menu-dropdown.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #e5e7eb;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: right;
            font-size: 0.875rem;
            cursor: pointer;
            position: relative;
        }

        .menu-item:hover {
            background: #374151;
            color: #ffffff;
            transform: translateX(-2px);
        }

        .menu-item:active {
            transform: scale(0.98);
        }

        .menu-item.delete-item {
            color: #ef4444;
        }

        .menu-item.delete-item:hover {
            background: #ef4444;
            color: #ffffff;
            transform: translateX(-2px);
        }

        .menu-item-form {
            width: 100%;
        }

        /* Product Icon */
        .product-icon {
            margin: 1rem 0 0.5rem 0;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0;
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .product-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: transparent;
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }

        .product-card:hover .product-icon::before {
            animation: none;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: transparent;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-image:not([src]) {
            display: none;
        }

        .product-image[src=""] {
            display: none;
        }

        .product-image-placeholder {
            color: #ffffff;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            transition: all 0.3s ease;
        }

        .product-image-placeholder:not([style*="display: none"]) {
            display: flex !important;
        }


        .product-card:hover .product-image-placeholder {
            transform: scale(1.05);
        }

        .product-card:hover .product-image-placeholder i {
            color: #ffffff;
        }

        /* Debug styles for images */
        .product-image {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-image-placeholder {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Ensure proper image display */
        .product-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        /* Fallback for broken images */
        .product-icon img[src=""],
        .product-icon img:not([src]) {
            display: none;
        }

        .product-icon img[src=""]+.product-image-placeholder,
        .product-icon img:not([src])+.product-image-placeholder {
            display: flex !important;
        }

        /* Ensure placeholder shows when image fails to load */
        .product-icon img[onerror] {
            position: relative;
        }

        .product-icon img[onerror]+.product-image-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        /* Debug: Add background to see if images are loading */
        .product-icon {
            background: transparent;
            position: relative;
        }

        .product-icon::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
            z-index: -1;
        }

        /* Ensure images are properly displayed */
        .product-icon img {
            z-index: 1;
            position: relative;
        }

        .product-image-placeholder {
            z-index: 1;
            position: relative;
        }

        /* Product Info */
        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 0.25rem 0;
            line-height: 1.4;
            position: relative;
            z-index: 1;
        }

        .product-name-english {
            font-size: 0.8rem;
            color: #d1d5db;
            margin: 0;
            line-height: 1.4;
            position: relative;
            z-index: 1;
        }

        /* Add some variety to product icons */
        .product-card:nth-child(6n+1) .product-icon {
            background: transparent;
        }

        .product-card:nth-child(6n+2) .product-icon {
            background: transparent;
        }

        .product-card:nth-child(6n+3) .product-icon {
            background: transparent;
        }

        .product-card:nth-child(6n+4) .product-icon {
            background: transparent;
        }

        .product-card:nth-child(6n+5) .product-icon {
            background: transparent;
        }

        .product-card:nth-child(6n+6) .product-icon {
            background: transparent;
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .products-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .product-card {
                padding: 0.75rem;
                min-height: 180px;
            }

            .product-icon {
                width: 80px;
                height: 80px;
                margin: 1rem 0 0.5rem 0;
            }

            .product-image {
                width: 60px;
                height: 60px;
            }

            .product-image-placeholder {
                font-size: 2rem;
            }

            .notification-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .notification {
                padding: 15px;
            }

            .breadcrumb-nav {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .breadcrumb-separator {
                display: none;
            }

            .page-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .back-btn {
                margin-right: 0;
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .product-card {
                min-height: 160px;
            }

            .product-icon {
                width: 70px;
                height: 70px;
                margin: 0.75rem 0 0.5rem 0;
            }

            .product-image {
                width: 50px;
                height: 50px;
            }

            .product-image-placeholder {
                font-size: 1.8rem;
            }

            .product-name {
                font-size: 0.8rem;
            }

            .product-name-english {
                font-size: 0.7rem;
            }
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

    <div class="products-container">
        <!-- Breadcrumb Navigation -->
        @if ($selectedCategory)
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.product.index') }}" class="breadcrumb-link">
                    <i class="fas fa-box"></i>
                    {{ __('All Products') }}
                </a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">
                    <i class="fas fa-tag"></i>
                    {{ $selectedCategory->getTranslation('name', app()->getLocale()) }}
                </span>
            </div>
        @endif

        <div class="page-header">
            <h1 class="page-title">
                @if ($selectedCategory)
                    {{ __('Products in') }}: {{ $selectedCategory->getTranslation('name', app()->getLocale()) }}
                @else
                    {{ __('Product Management') }}
                @endif
            </h1>
            <div class="page-actions">
                @if ($selectedCategory)
                    <a href="{{ route('dashboard.product.index') }}" class="back-btn">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('All Products') }}
                    </a>
                @endif
                <a href="{{ route('dashboard.product.create', $selectedCategory ? ['category_id' => $selectedCategory->id] : []) }}"
                    class="add-product-btn">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Product') }}
                </a>
            </div>
        </div>

        <div class="search-filter-bar">
            <div class="search-filter-content">
                <div class="search-box">
                    <input type="text" id="search-input" placeholder="{{ __('Search for products...') }}">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select">
                    <option value="">{{ __('All Categories') }}</option>
                    <option value="electronics">{{ __('Electronics') }}</option>
                    <option value="clothing">{{ __('Clothing') }}</option>
                    <option value="books">{{ __('Books') }}</option>
                    <option value="home">{{ __('Home') }}</option>
                </select>
                <select class="filter-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="in-stock">{{ __('In Stock') }}</option>
                    <option value="low-stock">{{ __('Low Stock') }}</option>
                    <option value="out-of-stock">{{ __('Out of Stock') }}</option>
                </select>
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    {{ __('Apply') }}
                </button>
            </div>
        </div>

        <div class="products-grid" id="productsGrid">
            @forelse ($products as $product)
                <div class="product-card"
                    onclick="window.location.href='{{ route('dashboard.product.show', $product) }}'">
                    <!-- Product Logo -->
                    <div class="product-logo">
                        <i class="fas fa-store"></i>
                    </div>

                    <!-- Three Dots Menu -->
                    <div class="product-menu">
                        <button class="menu-toggle" onclick="toggleMenu(this, event)">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="menu-dropdown">
                            <a href="{{ route('dashboard.product.edit', $product) }}" class="menu-item">
                                <i class="fas fa-edit"></i>
                                {{ __('تعديل') }}
                            </a>
                            <form action="{{ route('dashboard.product.destroy', $product) }}" method="POST"
                                class="menu-item-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="menu-item delete-item"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    <i class="fas fa-trash"></i>
                                    {{ __('حذف') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Product Icon -->
                    <div class="product-icon">
                        @if ($product->getFirstMedia('product_images'))
                            <img src="{{ $product->getFirstMedia('product_images')->getUrl() }}"
                                alt="{{ $product->name }}" class="product-image" loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="product-image-placeholder" style="display: none;">
                                <i class="fas fa-box-open"></i>
                            </div>
                        @else
                            <div class="product-image-placeholder">
                                <i class="fas fa-box-open"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="product-info">
                        <h3 class="product-name">
                            {{ $product->name }}
                            @if ($product->children()->count() > 0)
                                <span
                                    style="display: inline-block; margin-right: 0.5rem; background: #f59e0b; color: #000; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600;">
                                    <i class="fas fa-layer-group"></i> {{ $product->children()->count() }}
                                </span>
                            @endif
                        </h3>
                        <p class="product-name-english">{{ $product->name_en ?? $product->name }}</p>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>{{ __('No products found') }}</h3>
                    <p>{{ __('Create your first product') }}</p>
                    <a href="{{ route('dashboard.product.create', $selectedCategory ? ['category_id' => $selectedCategory->id] : []) }}"
                        class="add-product-btn">
                        <i class="fas fa-plus"></i>
                        {{ __('Add New Product') }}
                    </a>
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal" style="display: none;">
        <div class="delete-modal-overlay"></div>
        <div class="delete-modal-content">
            <div class="delete-modal-header">
                <h3>{{ __('Confirm Delete') }}</h3>
                <button type="button" class="delete-modal-close">&times;</button>
            </div>
            <div class="delete-modal-body">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="delete-message">
                    {{ __('Are you sure you want to delete the product') }} <strong id="productNameToDelete"></strong>?
                </p>
                <p class="delete-warning">
                    {{ __('This action cannot be undone.') }}
                </p>
            </div>
            <div class="delete-modal-footer">
                <button type="button" class="btn btn-secondary delete-modal-cancel">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-danger delete-modal-confirm">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete Product') }}
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Custom Delete Modal Styles */
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            font-family: 'Cairo', sans-serif;
        }

        .delete-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .delete-modal-content {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid #404040;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .delete-modal-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid #404040;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-modal-header h3 {
            color: #ffffff;
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .delete-modal-close {
            background: none;
            border: none;
            color: #888888;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .delete-modal-close:hover {
            background: #404040;
            color: #ffffff;
        }

        .delete-modal-body {
            padding: 2rem;
            text-align: center;
        }

        .delete-icon {
            margin-bottom: 1rem;
        }

        .delete-icon i {
            font-size: 3rem;
            color: #f59e0b;
        }

        .delete-message {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .delete-warning {
            color: #888888;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .delete-modal-footer {
            padding: 1rem 2rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #6b7280;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .delete-modal-content {
                width: 95%;
                margin: 1rem;
            }

            .delete-modal-header,
            .delete-modal-body,
            .delete-modal-footer {
                padding: 1rem;
            }

            .delete-modal-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script src="{{ asset('modules/product/js/products.js') }}"></script>

    <script>
        // Handle success message from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                if (typeof showSuccess === 'function') {
                    showSuccess('{{ __('Success') }}', '{{ __('Product updated successfully') }}');
                }
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Handle delete confirmation with custom modal
        let currentDeleteForm = null;
        let currentDeleteBtn = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('.product-name').textContent;
                const form = this.closest('form');

                // Store references for later use
                currentDeleteForm = form;
                currentDeleteBtn = this;

                // Show product name in modal
                document.getElementById('productNameToDelete').textContent = productName;

                // Show modal
                document.getElementById('deleteModal').style.display = 'flex';
            });
        });

        // Handle view and edit buttons - let them work as normal links
        // No event listeners needed - let the links work naturally

        // Debug: Check if buttons are clickable
        console.log('Edit buttons count:', document.querySelectorAll('.action-btn.edit').length);
        console.log('View buttons count:', document.querySelectorAll('.action-btn.view').length);

        // Add click event to test
        document.querySelectorAll('.action-btn.edit').forEach((btn, index) => {
            console.log(`Edit button ${index}:`, btn.href);
            btn.addEventListener('click', function(e) {
                console.log('Edit button clicked!', this.href);
                // Let the link work naturally - don't prevent default
            });
        });

        document.querySelectorAll('.action-btn.view').forEach((btn, index) => {
            console.log(`View button ${index}:`, btn.href);
            btn.addEventListener('click', function(e) {
                console.log('View button clicked!', this.href);
                // Let the link work naturally - don't prevent default
            });
        });

        // Handle modal close
        document.querySelector('.delete-modal-close').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Handle modal cancel
        document.querySelector('.delete-modal-cancel').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Handle modal overlay click
        document.querySelector('.delete-modal-overlay').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Toggle menu dropdown
        function toggleMenu(button, event) {
            event.stopPropagation(); // منع انتشار الحدث
            const dropdown = button.nextElementSibling;
            const isOpen = dropdown.classList.contains('show');

            // Close all other dropdowns
            document.querySelectorAll('.menu-dropdown.show').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                }
            });

            // Toggle current dropdown
            if (isOpen) {
                dropdown.classList.remove('show');
            } else {
                dropdown.classList.add('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.product-menu')) {
                document.querySelectorAll('.menu-dropdown.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Handle delete confirmation
        document.querySelector('.delete-modal-confirm').addEventListener('click', function() {
            if (currentDeleteForm && currentDeleteBtn) {
                const originalText = currentDeleteBtn.innerHTML;

                // Show loading state
                currentDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Deleting...') }}';
                currentDeleteBtn.disabled = true;

                // Hide modal
                document.getElementById('deleteModal').style.display = 'none';

                // Send AJAX delete request
                fetch(currentDeleteForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'DELETE'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success notification
                            showSuccessNotification(data.message, null, 'deleted');

                            // Remove the card from grid
                            const card = currentDeleteBtn.closest('.product-card');
                            if (card) {
                                card.style.transition = 'all 0.3s ease';
                                card.style.opacity = '0';
                                card.style.transform = 'scale(0.8)';
                                setTimeout(() => {
                                    card.remove();
                                }, 300);
                            }
                        } else {
                            showErrorNotification(data.message);
                            // Restore button state
                            currentDeleteBtn.innerHTML = originalText;
                            currentDeleteBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        showErrorNotification('{{ __('An error occurred while deleting the product') }}');
                        // Restore button state
                        currentDeleteBtn.innerHTML = originalText;
                        currentDeleteBtn.disabled = false;
                    });
            }
        });

        // Handle ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('deleteModal').style.display = 'none';
                currentDeleteForm = null;
                currentDeleteBtn = null;
            }
        });

        // Show and auto-hide notifications
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

        // Professional Success Notification
        function showSuccessNotification(message, productId = null, action = 'deleted') {
            const notification = document.createElement('div');
            notification.className = 'notification success professional-notification';
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
                    <div class="notification-message">${message}</div>
                    ${productId ? `
                                                        <div class="notification-details">
                                                            <i class="fas fa-info-circle"></i>
                                                            {{ __('Product') }} #${productId} ${action === 'deleted' ? '{{ __('has been permanently deleted') }}' : action === 'updated' ? '{{ __('has been updated successfully') }}' : '{{ __('has been created successfully') }}'}
                                                        </div>
                                                        ` : ''}
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

        // Professional Error Notification
        function showErrorNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification error professional-notification';
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

            // Auto-hide after 5 seconds
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }, 5000);

            // Progress bar animation
            const progressBar = notification.querySelector('.notification-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = 'width 5s linear';
            }
        }
    </script>

    <style>
        /* Professional Notification Styles */
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
    </style>
@endpush
