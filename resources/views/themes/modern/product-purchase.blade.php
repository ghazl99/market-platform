@extends('themes.app')
@section('title', __('Product') . ' - ' . $product->name)
@push('styles')
    <style>
        /* Product Purchase Page Specific Styles */
        .product-purchase-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .product-purchase-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .product-purchase-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Breadcrumb Section */
        .breadcrumb-section {
            padding: 1rem 0;
            background: #ffffff;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            margin-bottom: 2rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .breadcrumb-item {
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.3s ease;
        }

        .breadcrumb-item:hover {
            color: #667eea;
        }

        .breadcrumb-item.active {
            color: #333;
            font-weight: 600;
        }

        .breadcrumb-separator {
            color: #999;
            font-size: 0.8rem;
        }

        /* Product Purchase Card */
        .product-purchase-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(20px);
            margin-bottom: 2rem;
        }

        .product-purchase-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2, #3b82f6, #8b5cf6);
            z-index: 1;
        }

        .product-purchase-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 3rem;
        }

        /* Page Title */
        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Product Image Section */
        .product-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 20px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .product-image-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .product-image-wrapper {
            position: relative;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow:
                0 20px 40px rgba(102, 126, 234, 0.3),
                0 0 0 6px rgba(255, 255, 255, 0.1),
                0 0 0 12px rgba(102, 126, 234, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }

        .product-image-wrapper:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow:
                0 30px 60px rgba(102, 126, 234, 0.4),
                0 0 0 8px rgba(255, 255, 255, 0.2),
                0 0 0 16px rgba(102, 126, 234, 0.2);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: all 0.4s ease;
        }

        .api-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #667eea 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
            z-index: 3;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Product Info Section */
        .product-info-section {
            padding: 2rem;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .product-title {
            font-size: 2rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .product-category {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
            opacity: 0.8;
        }

        .product-rating {
            display: flex;
            justify-content: center;
            gap: 0.3rem;
            margin-bottom: 1.5rem;
        }

        .star {
            font-size: 1.3rem;
            color: #ddd;
            transition: all 0.3s ease;
        }

        .star.active {
            color: #f59e0b;
            transform: scale(1.1);
        }

        /* Quantity Info Cards */
        .quantity-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quantity-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .quantity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .quantity-card:hover::before {
            left: 100%;
        }

        .quantity-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .quantity-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .quantity-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #667eea;
        }

        /* Price Section */
        .price-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .price-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
            }
        }

        .price-label {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .price-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: #667eea;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        }

        .old-price {
            font-size: 1.1rem;
            color: #666;
            text-decoration: line-through;
            margin-left: 0.5rem;
            opacity: 0.7;
        }

        .dollar-price {
            font-size: 1rem;
            color: #666;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Form Section */
        .form-section {
            background: #ffffff;
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            color: #333;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow:
                0 0 0 4px rgba(102, 126, 234, 0.1),
                0 8px 20px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-control:disabled {
            background: #f8f9fa;
            color: #999;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .form-control::placeholder {
            color: #999;
            opacity: 1;
        }

        .int-format-jaafar {
            text-align: center;
            font-weight: 600;
        }

        .input-group {
            position: relative;
            display: flex;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .input-group .form-control {
            border-radius: 0;
            border-right: none;
        }

        .input-group .btn {
            border-radius: 0;
            border-left: none;
            padding: 1rem 1.25rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #333;
            cursor: pointer;
            user-select: none;
        }

        .form-check-input:checked+.form-check-label {
            color: #667eea;
            font-weight: 600;
        }

        /* Error States */
        .form-control.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .form-control.error+.error-message {
            display: block;
        }

        /* Purchase Button Section */
        .purchase-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .purchase-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.05) 0%, transparent 50%, rgba(118, 75, 162, 0.05) 100%);
            pointer-events: none;
        }

        .purchase-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.25rem 3rem;
            font-size: 1.2rem;
            font-weight: 800;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                0 10px 30px rgba(102, 126, 234, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            min-width: 250px;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .purchase-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .purchase-btn:hover::before {
            left: 100%;
        }

        .purchase-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow:
                0 20px 40px rgba(102, 126, 234, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .purchase-btn:active {
            transform: translateY(-2px) scale(1.01);
        }

        .purchase-btn i {
            font-size: 1.3rem;
            margin-left: 0.75rem;
        }

        /* Description Section */
        .description-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            margin-top: 2rem;
        }

        .description-text {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #666;
            font-weight: 500;
        }

        /* Utility Classes */
        .d-none {
            display: none !important;
        }

        .btn {
            display: inline-block;
            font-weight: 600;
            line-height: 1.5;
            color: #333;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            color: white;
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-primary:hover {
            color: white;
            background-color: #5a67d8;
            border-color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* Loading Animation */
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .product-purchase-container {
                padding: 0 1.5rem;
            }

            .card-content {
                padding: 2rem;
            }

            .product-info {
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .product-purchase-container {
                padding: 0 1rem;
            }

            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .page-subtitle {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            .card-content {
                padding: 1.5rem;
            }

            .product-info {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .product-image-wrapper {
                width: 180px;
                height: 180px;
                margin: 0 auto;
            }

            .product-details {
                align-items: center;
            }

            .product-title {
                font-size: 1.5rem;
                margin-bottom: 0.75rem;
            }

            .product-category {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .rating-stars {
                margin-bottom: 1rem;
            }

            .quantity-cards {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .quantity-card {
                padding: 1rem;
            }

            .quantity-label {
                font-size: 0.9rem;
            }

            .quantity-value {
                font-size: 1.25rem;
            }

            .price-section {
                margin-bottom: 1.5rem;
            }

            .price-value {
                font-size: 2rem;
            }

            .price-old {
                font-size: 1.25rem;
            }

            .price-dollar {
                font-size: 0.9rem;
            }

            .form-section {
                margin-bottom: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .form-check {
                margin-bottom: 1rem;
            }

            .form-check-label {
                font-size: 0.8rem;
            }

            .purchase-btn {
                width: 100%;
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            .description-section {
                padding: 1.5rem;
            }

            .description-title {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .description-text {
                font-size: 0.9rem;
                line-height: 1.6;
            }
        }

        @media (max-width: 480px) {
            .product-purchase-container {
                padding: 0 0.75rem;
            }

            .page-header {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .page-title {
                font-size: 1.75rem;
                margin-bottom: 0.5rem;
            }

            .page-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .card-content {
                padding: 1rem;
            }

            .product-info {
                gap: 1rem;
            }

            .product-image-wrapper {
                width: 150px;
                height: 150px;
            }

            .product-title {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .product-category {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }

            .rating-stars {
                margin-bottom: 0.75rem;
            }

            .quantity-cards {
                gap: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .quantity-card {
                padding: 0.75rem;
            }

            .quantity-label {
                font-size: 0.8rem;
            }

            .quantity-value {
                font-size: 1.1rem;
            }

            .price-section {
                margin-bottom: 1rem;
            }

            .price-value {
                font-size: 1.75rem;
            }

            .price-old {
                font-size: 1.1rem;
            }

            .price-dollar {
                font-size: 0.8rem;
            }

            .form-section {
                margin-bottom: 1rem;
            }

            .form-group {
                margin-bottom: 0.75rem;
            }

            .form-label {
                font-size: 0.8rem;
                margin-bottom: 0.25rem;
            }

            .form-control {
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }

            .form-check {
                margin-bottom: 0.75rem;
            }

            .form-check-label {
                font-size: 0.75rem;
            }

            .purchase-btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }

            .description-section {
                padding: 1rem;
            }

            .description-title {
                font-size: 1.1rem;
                margin-bottom: 0.75rem;
            }

            .description-text {
                font-size: 0.8rem;
                line-height: 1.5;
            }
        }
    </style>
@endpush
@section('content') <!-- Breadcrumb Section -->
    <!-- Product Purchase Section -->
    <div class="product-purchase-card" id="product-requesr-main" product-request-id="7" product-request-apiid="7"
        product-request-skipping="2" product-request-pricesource="Level">
        <div class="card-content">
            @php
                $media = $product->getFirstMedia('product_images');
            @endphp
            <!-- Product Image Section -->
            <div class="product-image-section">
                <div class="product-image-wrapper">
                    @if ($media)
                        <img id="product-request-image" src="{{ route('product.image', $media->id) }}" class="product-image"
                            alt="{{ $product->name }}">
                    @else
                        <div id="product-request-image" class="product-image"
                            style="display:flex; align-items:center; justify-content:center; background:#e9ecef;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="100%" height="100%">
                                <!-- SVG للمنتج الافتراضي -->
                                <rect x="8" y="12" width="48" height="6" fill="#6c757d" rx="1" />
                                <text x="32" y="16" font-size="4" text-anchor="middle" fill="#fff"
                                    font-family="Arial, sans-serif">STORE</text>
                                <rect x="6" y="20" width="52" height="36" fill="#e9ecef" rx="2"
                                    stroke="#adb5bd" stroke-width="1" />
                                <rect x="28" y="40" width="8" height="16" fill="#343a40" rx="1" />
                                <rect x="12" y="26" width="8" height="12" fill="#ced4da" stroke="#adb5bd"
                                    stroke-width="0.5" />
                                <rect x="44" y="26" width="8" height="12" fill="#ced4da" stroke="#adb5bd"
                                    stroke-width="0.5" />
                            </svg>
                        </div>
                    @endif
                    <div class="api-badge" id="product-request-api">
                        <i class="fas fa-code"></i>
                    </div>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="product-info-section">
                <h4 class="product-title" id="product-request-name"> {{ $product->name }}</h4>
                <!-- الأقسام -->
                {{-- <div class="mb-3 product-category-section">
                                <h5>{{ __('Categories') }}</h5>
                                <div class="product-categories">
                                    @foreach ($product->categories as $category)
                                        <span class="category-badge">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </div> --}}

                <!-- الخصائص -->
                @if ($product->attributes->count() > 0)
                    <div class="mb-3 product-category-section">
                        <h5>{{ __('Attributes') }}</h5>
                        <div class="product-attributes">
                            @foreach ($product->attributes as $attribute)
                                @php
                                    $value = json_decode($attribute->pivot->value, true);
                                    $currentValue = $value[app()->getLocale()] ?? (reset($value) ?? '');
                                @endphp
                                <span class="product-attribute">
                                    {{ $attribute->name }}: {{ $currentValue }}
                                    @if ($attribute->unit)
                                        ({{ $attribute->unit }})
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity Info Cards -->
                <div class="quantity-cards">
                    <div class="quantity-card">
                        <div class="quantity-label">{{ __('Minimum quantity') }}</div>
                        <div class="quantity-value" id="Product-request-min">{{ $product->min_quantity }}</div>
                    </div>
                    <div class="quantity-card">
                        <div class="quantity-label">{{ __('Maximum quantity') }}</div>
                        <div class="quantity-value" id="Product-request-max">{{ $product->max_quantity }}</div>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="price-label">السعر النهائي</div>
                    <div class="price-value">
                        <span id="product-request-TotalPrice">{{ number_format($product->price_with_group_profit, 2) }}</span> $
                    </div>
                </div>

            </div>
            @if ($product->status)
                <!-- Form Section -->
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="form-section">
                        <!-- Quantity Section -->
                        <div class="form-group">
                            <label class="form-label">{{ __('Quantity') }}</label>
                            <div class="input-group">
                                <input name="quantity" id="quantity" placeholder="{{ __('Quantity') }}"
                                    class="form-control int-format-jaafar" value="1">
                                <span class="input-group-text">{{ number_format($product->price_with_group_profit, 2) }} $</span>
                            </div>
                        </div>

                        @if ($product->store->type === 'digital')
                            <div class="form-group">
                                <label class="form-label"> {{ __('Player ID') }}</label>
                                <div class="input-group">
                                    <input name="player_id" id="player_id" placeholder="{{ __('Enter Player ID') }}"
                                        required class="form-control">

                                </div>
                            </div>
                        @endif
                        <div class="form-group d-none" id="product-request-playername-container">
                            <div class="input-group">
                                <img src="/assets/images/media/ajax-loader.gif" id="loadingUserInfoImage"
                                    style="display: block;width:35px">
                                <input name="playerId" id="product-request-playername" class="form-control">
                            </div>
                        </div>

                    </div>

                    <!-- Purchase Button Section -->
                    <div class="purchase-section">
                        <button id="product-request-buyid" type="submit" class="purchase-btn">
                            <i class="fas fa-credit-card"></i>
                            {{ __('Complete the purchase') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const totalPriceDisplay = document.getElementById('product-request-TotalPrice');
            const unitPrice = parseFloat("{{ $product->price_with_group_profit }}");

            function updateTotalPrice() {
                let quantity = parseInt(quantityInput.value) || 1;

                const minQty = parseInt("{{ $product->min_quantity }}");
                const maxQty = parseInt("{{ $product->max_quantity }}") || Infinity;

                if (quantity < minQty) quantity = minQty;
                if (quantity > maxQty) quantity = maxQty;

                quantityInput.value = quantity;

                const total = (unitPrice * quantity).toFixed(2);
                totalPriceDisplay.textContent = total;
            }

            updateTotalPrice();
            quantityInput.addEventListener('input', updateTotalPrice);
        });
    </script>
@endpush
