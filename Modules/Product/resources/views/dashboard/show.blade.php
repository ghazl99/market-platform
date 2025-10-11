@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Product Details'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Notification Styles for Product Show Page */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 300px;
            max-width: 400px;
            transform: translateX(0);
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .notification.success {
            border-left-color: #10b981;
            background: #f0fdf4;
        }

        .notification.error {
            border-left-color: #ef4444;
            background: #fef2f2;
        }

        .notification.warning {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }

        .notification.info {
            border-left-color: #3b82f6;
            background: #eff6ff;
        }

        .notification i {
            font-size: 1.25rem;
        }

        .notification.success i {
            color: #10b981;
        }

        .notification.error i {
            color: #ef4444;
        }

        .notification.warning i {
            color: #f59e0b;
        }

        .notification.info i {
            color: #3b82f6;
        }

        .notification span {
            font-weight: 500;
            color: #374151;
        }

        /* Edit Button Styles */
        .edit-btn {
            background: #059669;
            color: white;
            border: 1px solid #059669;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .edit-btn:hover {
            background: #047857;
            border-color: #047857;
            transform: translateY(-1px);
            text-decoration: none;
        }

        /* Success Message in Header */
        .success-message {
            background: #f0fdf4;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #065f46;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .success-message i {
            color: #10b981;
            font-size: 1.1rem;
        }

        .success-message {
            transition: all 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notification {
                right: 10px;
                left: 10px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Notifications -->
    @if (session('success'))
        <div class="notification success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="notification error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session('warning'))
        <div class="notification warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="notification info">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="notification error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ __('Please fix the errors below') }}</span>
        </div>
    @endif

    <div class="product-show-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Product Details') }}</h1>
            @if (session('success'))
                <div class="alert alert-success"
                    style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    <span style="font-weight: 500;">{{ session('success') }}</span>
                </div>
            @endif
            <div class="page-actions">
                <a href="{{ route('dashboard.product.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Products') }}
                </a>
                <a href="{{ route('dashboard.product.edit', $product->id) }}" class="edit-btn">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Product') }}
                </a>
            </div>
        </div>

        <div class="product-details">
            <div class="product-main">
                <div class="product-image">
                    @if ($product->getFirstMedia('product_images'))
                        <img src="{{ $product->getFirstMedia('product_images')->getUrl() }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                            <span>{{ __('No Image') }}</span>
                        </div>
                    @endif
                </div>

                <div class="product-info">
                    <h2 class="product-name">{{ $product->getTranslation('name', app()->getLocale()) }}</h2>
                    <p class="product-description">{{ $product->getTranslation('description', app()->getLocale()) }}</p>

                    <div class="product-price">
                        <span class="price">{{ number_format($product->price, 2) }} {{ __('SAR') }}</span>
                        @if ($product->sale_price)
                            <span class="sale-price">{{ number_format($product->sale_price, 2) }}
                                {{ __('SAR') }}</span>
                        @endif
                    </div>

                    <div class="product-status">
                        <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                            {{ $product->is_active ? __('Active') : __('Inactive') }}
                        </span>
                        @if ($product->is_featured)
                            <span class="featured-badge">{{ __('Featured') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="product-details-grid">
                <div class="detail-section">
                    <h3>{{ __('Categories') }}</h3>
                    <div class="categories-list">
                        @forelse($product->categories as $category)
                            <span class="category-tag">{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                        @empty
                            <span class="no-data">{{ __('No categories assigned') }}</span>
                        @endforelse
                    </div>
                </div>

                <div class="detail-section">
                    <h3>{{ __('Product Information') }}</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>{{ __('SKU') }}:</label>
                            <span>{{ $product->sku ?? __('Not set') }}</span>
                        </div>
                        <div class="info-item">
                            <label>{{ __('Stock Quantity') }}:</label>
                            <span>{{ $product->stock_quantity ?? 0 }}</span>
                        </div>
                        <div class="info-item">
                            <label>{{ __('Weight') }}:</label>
                            <span>{{ $product->weight ?? __('Not set') }}</span>
                        </div>
                        <div class="info-item">
                            <label>{{ __('Dimensions') }}:</label>
                            <span>{{ $product->dimensions ?? __('Not set') }}</span>
                        </div>
                    </div>
                </div>

                @if ($product->seo_title || $product->seo_description)
                    <div class="detail-section">
                        <h3>{{ __('SEO Information') }}</h3>
                        <div class="seo-info">
                            @if ($product->seo_title)
                                <div class="seo-item">
                                    <label>{{ __('SEO Title') }}:</label>
                                    <span>{{ $product->seo_title }}</span>
                                </div>
                            @endif
                            @if ($product->seo_description)
                                <div class="seo-item">
                                    <label>{{ __('SEO Description') }}:</label>
                                    <span>{{ $product->seo_description }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="detail-section">
                    <h3>{{ __('Timestamps') }}</h3>
                    <div class="timestamps">
                        <div class="timestamp-item">
                            <label>{{ __('Created At') }}:</label>
                            <span>{{ $product->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        <div class="timestamp-item">
                            <label>{{ __('Updated At') }}:</label>
                            <span>{{ $product->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>

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

        // Auto-hide notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            });

            // Auto-hide success message in header after 5 seconds
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
@endpush
