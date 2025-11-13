@extends('themes.app')
@section('title', __('Balance'))
@push('styles')
    <style>
        
        /* Add Balance Page Styles */
        .add-balance-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .add-balance-section::before {
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

        .add-balance-container {
            max-width: 1000px;
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

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        /* Current Balance */
        .current-balance {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            border: 1px solid rgba(102, 126, 234, 0.1);
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .current-balance::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #3b82f6, #8b5cf6);
        }

        .balance-label {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .balance {

            font-size: 2.5rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .balance-currency {
            font-size: 1.2rem;
            color: #999;
            font-weight: 500;
        }

        /* Tabs System */
        .tabs-container {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .tabs-header {
            display: flex;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .tab-button {
            flex: 1;
            padding: 1.5rem 2rem;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .tab-button:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #333;
        }

        .tab-button.active {
            background: #ffffff;
            color: #667eea;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .tab-content {
            display: none;
            padding: 2.5rem;
        }

        .tab-content.active {
            display: block;
        }

        .tab-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-title i {
            color: #667eea;
            font-size: 1.3rem;
        }

        .tab-description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Payment Methods Grid */
        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .payment-method-card {
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .payment-method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .payment-method-card:hover::before {
            transform: scaleX(1);
        }

        .payment-method-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
        }

        .payment-method-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }

        .payment-method-card.selected::before {
            transform: scaleX(1);
        }

        .payment-method-image {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 1rem;
            border-radius: 12px;
            background: #f8f9fa;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover .payment-method-image {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .payment-method-name {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-top: 0.5rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 150px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #ffffff;
            color: #333;
            border: 2px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
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
            .add-balance-container {
                padding: 0 1.5rem;
            }

            .payment-methods-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .add-balance-container {
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

            .current-balance {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .balance-label {
                font-size: 1rem;
                margin-bottom: 0.75rem;
            }

            .balance {
                font-size: 2.5rem;
            }

            .tabs-container {
                margin-bottom: 1.5rem;
            }

            .tabs-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .tab-button {
                padding: 1rem;
                font-size: 1rem;
            }

            .tab-content {
                padding: 1.5rem;
            }

            .payment-methods-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .payment-method-card {
                padding: 1rem;
            }

            .payment-method-image {
                width: 50px;
                height: 50px;
                margin-bottom: 0.75rem;
            }

            .payment-method-name {
                font-size: 0.9rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .add-balance-container {
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

            .current-balance {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .balance-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .balance {
                font-size: 2rem;
            }

            .tabs-container {
                margin-bottom: 1rem;
            }

            .tabs-header {
                gap: 0.25rem;
            }

            .tab-button {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .tab-content {
                padding: 1rem;
            }

            .payment-methods-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .payment-method-card {
                padding: 0.75rem;
            }

            .payment-method-image {
                width: 40px;
                height: 40px;
                margin-bottom: 0.5rem;
            }

            .payment-method-name {
                font-size: 0.8rem;
            }

            .action-buttons {
                gap: 0.75rem;
            }

            .btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="add-balance-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Add Balance') }}</h1>
            <p class="page-subtitle">{{ __('Choose the appropriate payment method to add balance to your wallet') }}</p>
        </div>

        <!-- Current Balance -->
        <div class="current-balance">
            <div class="balance-label">{{ __('Current Balance') }}</div>
            <div class="balance">
                {{ number_format(Auth::user()->walletForStore()->first()?->balance ?? 0, 2) }}
            </div>
            <div class="balance-currency">{{ __('USD') }}</div>
        </div>

        <!-- Tabs System -->
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" data-tab="manual">
                    <i class="fas fa-hand-holding-usd"></i>
                    {{ __('Manual Payment') }}
                </button>
                <button class="tab-button" data-tab="electronic">
                    <i class="fas fa-credit-card"></i>
                    {{ __('Electronic Payment') }}
                </button>
            </div>

            <!-- Manual Payment Tab -->
            <div class="tab-content active" id="manualTab">
                <h3 class="tab-title">
                    <i class="fas fa-file-invoice"></i>
                    {{ __('Manual Payment Methods') }}
                </h3>
                <p class="tab-description">
                    {{ __('Choose the appropriate manual payment method. The request will be sent to the administrator for review and approval.') }}
                </p>
                <div class="payment-methods-grid">
                    @foreach ($paymentMethods as $method)
                        <a href="{{ Route('payment-method.show', $method) }}" class="payment-method-card"
                            data-gateway="{{ $method->gateway }}">
                            @php $media = $method->getFirstMedia('payment_method_images'); @endphp

                            @if ($method->getFirstMediaUrl('image'))
                                <img class="payment-method-image" src="{{ route('payment.methode.image', $media->id) }}"
                                    alt="{{ $method->getTranslation('name', app()->getLocale()) }}">
                            @else
                                <img class="payment-method-image" src="{{ asset('assets/img/payment.png') }}"
                                    alt="{{ $method->name }}">
                            @endif

                            <div class="payment-method-name">
                                {{ $method->getTranslation('name', app()->getLocale()) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Electronic Payment Tab -->
            <div class="tab-content" id="electronicTab">
                <h3 class="tab-title">
                    <i class="fas fa-shield-alt"></i>
                    {{ __('Electronic Payment Gateways') }}
                </h3>
                <p class="tab-description">
                    {{ __('Choose the appropriate electronic payment gateway. You will be redirected for immediate payment.') }}
                </p>
                <div class="payment-methods-grid">
                    <!-- يمكنك إضافة بوابات الدفع الإلكترونية هنا -->
                </div>
            </div>
        </div>
    </div>
@endsection
