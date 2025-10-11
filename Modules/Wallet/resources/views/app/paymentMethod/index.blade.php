@extends('core::store.layouts.app')
@section('title', __('Balance'))
@push('styles')
    <style>
        /* Add Balance Page Styles */
        .add-balance-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .current-balance {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .balance-label {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .balance-currency {
            font-size: 1.2rem;
            color: var(--text-light);
        }

        /* Tabs System */
        .tabs-container {
            background: var(--bg-primary);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .tabs-header {
            display: flex;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        .tab-button {
            flex: 1;
            padding: 1.5rem 2rem;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .tab-button:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .tab-button.active {
            background: var(--bg-primary);
            color: var(--primary-color);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }

        .tab-content {
            display: none;
            padding: 2rem;
        }

        .tab-content.active {
            display: block;
        }

        .tab-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-title i {
            color: var(--primary-color);
        }

        .tab-description {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .payment-method-card {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .payment-method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .payment-method-card:hover::before {
            transform: scaleX(1);
        }

        .payment-method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .payment-method-card.selected {
            border-color: var(--primary-color);
            background: var(--bg-accent);
        }

        .payment-method-card.selected::before {
            transform: scaleX(1);
        }

        .payment-method-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 1rem;
            border-radius: 8px;
            background: var(--bg-primary);
            padding: 0.5rem;
            border: 1px solid var(--border-light);
        }

        .payment-method-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

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
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .add-balance-container {
                padding: 1rem;
            }

            .tabs-header {
                flex-direction: column;
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
            }

            .action-buttons {
                flex-direction: column;
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
            <div class="balance-amount">
                {{ number_format(Auth::user()->wallet->balance ?? 0, 2) }}
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
