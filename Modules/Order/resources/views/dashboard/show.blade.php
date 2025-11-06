@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Order Details'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">
@endpush

@section('content')
    <!-- Notifications -->
    <div class="notification-container">
        @if (session('success'))
            <div class="notification success">
                <div class="notification-icon">
                    <i class="fas fa-check"></i>
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
            <div class="notification error">
                <div class="notification-icon">
                    <i class="fas fa-times"></i>
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
            <div class="notification warning">
                <div class="notification-icon">
                    <i class="fas fa-exclamation"></i>
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
            <div class="notification info">
                <div class="notification-icon">
                    <i class="fas fa-info"></i>
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
            <div class="notification error">
                <div class="notification-icon">
                    <i class="fas fa-times"></i>
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

    <div class="order-detail-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <a href="{{ route('dashboard.order.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Orders') }}
                </a>
                <div class="header-title">
                    <h1 class="page-title">{{ __('Order Details') }}</h1>
                    <span class="order-number">#{{ $order->id }}</span>
                </div>
            </div>
            <div class="header-status">
                <span class="order-status-badge {{ $order->status }}">
                    @switch($order->status)
                        @case('completed')
                            <i class="fas fa-check-circle"></i>
                            {{ __('Completed') }}
                        @break

                        @case('pending')
                            <i class="fas fa-cog"></i>
                            {{ __('Automatic Processing') }}
                        @break

                        @case('processing')
                            <i class="fas fa-hand-paper"></i>
                            {{ __('Manual Processing') }}
                        @break

                        @case('canceled')
                        @case('cancelled')
                            <i class="fas fa-times-circle"></i>
                            {{ __('Cancelled') }}
                        @break

                        @default
                            {{ __('Status') }}: {{ ucfirst($order->status) }}
                    @endswitch
                </span>
            </div>
        </div>

        @php
            $firstItem = $order->items->first();
            $product = $firstItem->product ?? null;
            $productMedia = $product ? $product->getFirstMedia('product_images') : null;
        @endphp

        <!-- Product Image Hero Section -->
        <div class="product-hero-section">
            <div class="product-image-container">
                @if ($productMedia)
                    <img src="{{ route('dashboard.product.image', $productMedia->id) }}"
                         alt="{{ $product->name ?? __('Product Image') }}"
                         class="product-hero-image"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="product-image-placeholder" style="display: none;">
                        <i class="fas fa-image"></i>
                        <span>{{ __('No Image Available') }}</span>
                    </div>
                @else
                    <div class="product-image-placeholder">
                        <i class="fas fa-box"></i>
                        <span>{{ __('No Image Available') }}</span>
                    </div>
                @endif
            </div>
            @if ($product)
                <div class="product-hero-info">
                    <h2 class="product-hero-name">
                        @if (is_array($product->name))
                            {{ $product->name[app()->getLocale()] ?? reset($product->name) }}
                        @else
                            {{ $product->name }}
                        @endif
                    </h2>
                    <div class="product-hero-meta">
                        <span class="product-id-badge">
                            <i class="fas fa-tag"></i>
                            {{ __('Product ID') }}: #{{ $product->id }}
                        </span>
                        @if (isset($product->price) && $product->price > 0)
                            <span class="product-price-badge">
                                <i class="fas fa-money-bill-wave"></i>
                                {{ number_format($product->price, 2) }} {{ __('SAR') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="order-content-grid">
            <!-- Order Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('Order Information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Order ID') }}</span>
                                <span class="info-value">#{{ $order->id }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Order Date') }}</span>
                                <span class="info-value">{{ $order->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Order Time') }}</span>
                                <span class="info-value">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                        @if ($order->updated_at && $order->updated_at != $order->created_at)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">{{ __('Last Updated') }}</span>
                                    <span class="info-value">{{ $order->updated_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                        @endif
                        @if ($order->store)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">{{ __('Store') }}</span>
                                    <span class="info-value">
                                        @if ($order->store->name)
                                            {{ is_array($order->store->name) ? ($order->store->name[app()->getLocale()] ?? reset($order->store->name)) : $order->store->name }}
                                        @else
                                            {{ __('Store') }}
                                        @endif
                                         #{{ $order->store_id }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Total Items') }}</span>
                                <span class="info-value">{{ $order->items->sum('quantity') }} {{ __('item(s)') }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Payment Method') }}</span>
                                <span class="info-value">{{ __('Electronic Wallet') }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">{{ __('Payment Status') }}</span>
                                <span class="info-value">
                                    @if ($order->payment_status === 'paid')
                                        <span class="status-badge success">
                                            <i class="fas fa-check"></i>
                                            {{ __('Paid') }}
                                        </span>
                                    @else
                                        <span class="status-badge warning">
                                            <i class="fas fa-exclamation"></i>
                                            {{ __('Unpaid') }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @if ($order->cancel_reason)
                            <div class="info-item full-width">
                                <div class="info-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">{{ __('Cancellation Reason') }}</span>
                                    <span class="info-value cancel-reason">{{ $order->cancel_reason }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i>
                        {{ __('Customer Information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="customer-card">
                        <div class="customer-avatar">
                            @if ($order->user && $order->user->name)
                                {{ strtoupper(mb_substr(is_array($order->user->name) ? ($order->user->name[app()->getLocale()] ?? reset($order->user->name)) : $order->user->name, 0, 1)) }}
                            @else
                                G
                            @endif
                        </div>
                        <div class="customer-details">
                            <h4 class="customer-name">
                                @if ($order->user && $order->user->name)
                                    {{ is_array($order->user->name) ? ($order->user->name[app()->getLocale()] ?? reset($order->user->name)) : $order->user->name }}
                                @else
                                    {{ __('Guest') }}
                                @endif
                            </h4>
                            <div class="customer-info-list">
                                <div class="customer-info-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $order->user->email ?? __('No email') }}</span>
                                </div>
                                @if ($order->user)
                                    <div class="customer-info-item">
                                        <i class="fas fa-id-card"></i>
                                        <span>{{ __('User ID') }}: #{{ $order->user->id }}</span>
                                    </div>
                                    @if ($order->user->phone)
                                        <div class="customer-info-item">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ $order->user->phone }}</span>
                                        </div>
                                    @endif
                                    @if ($order->user->birth_date)
                                        <div class="customer-info-item">
                                            <i class="fas fa-birthday-cake"></i>
                                            <span>{{ __('Birth Date') }}: {{ $order->user->birth_date->format('Y-m-d') }}</span>
                                        </div>
                                    @endif
                                    @if ($order->user->address)
                                        <div class="customer-info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ __('Address') }}: 
                                                @if (is_array($order->user->address))
                                                    {{ $order->user->address[app()->getLocale()] ?? implode(', ', $order->user->address) }}
                                                @else
                                                    {{ $order->user->address }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if ($order->user->city)
                                        <div class="customer-info-item">
                                            <i class="fas fa-city"></i>
                                            <span>{{ __('City') }}: 
                                                @if (is_array($order->user->city))
                                                    {{ $order->user->city[app()->getLocale()] ?? implode(', ', $order->user->city) }}
                                                @else
                                                    {{ $order->user->city }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if ($order->user->postal_code)
                                        <div class="customer-info-item">
                                            <i class="fas fa-mail-bulk"></i>
                                            <span>{{ __('Postal Code') }}: {{ $order->user->postal_code }}</span>
                                        </div>
                                    @endif
                                    @if ($order->user->country)
                                        <div class="customer-info-item">
                                            <i class="fas fa-globe"></i>
                                            <span>{{ __('Country') }}: {{ $order->user->country }}</span>
                                        </div>
                                    @endif
                                    @if ($order->user->last_login_at)
                                        <div class="customer-info-item">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>{{ __('Last Login') }}: {{ $order->user->last_login_at->format('Y-m-d H:i') }}</span>
                                        </div>
                                    @endif
                                    @if ($order->user->created_at)
                                        <div class="customer-info-item">
                                            <i class="fas fa-calendar-plus"></i>
                                            <span>{{ __('Member Since') }}: {{ $order->user->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics Card -->
            @if ($order->user)
                @php
                    $userWallet = $order->user && $order->user->wallets 
                        ? $order->user->wallets->where('store_id', $order->store_id)->first() 
                        : null;
                    $userOrdersCount = $order->user && $order->store_id
                        ? \Modules\Order\Models\Order::where('user_id', $order->user->id)
                            ->where('store_id', $order->store_id)
                            ->count()
                        : 0;
                    $userTotalSpent = $order->user && $order->store_id
                        ? (float) \Modules\Order\Models\Order::where('user_id', $order->user->id)
                            ->where('store_id', $order->store_id)
                            ->where('status', 'completed')
                            ->sum('total_amount')
                        : 0;
                    $userAverageOrder = $userOrdersCount > 0 && $userTotalSpent > 0 
                        ? (float) ($userTotalSpent / $userOrdersCount) 
                        : 0;
                @endphp
                <div class="info-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line"></i>
                            {{ __('Customer Statistics') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="customer-stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon orders">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $userOrdersCount }}</div>
                                    <div class="stat-label">{{ __('Total Orders') }}</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon spent">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ number_format($userTotalSpent, 2) }}</div>
                                    <div class="stat-label">{{ __('Total Spent') }} ({{ __('SAR') }})</div>
                                </div>
                            </div>
                            @if ($userWallet && isset($userWallet->balance))
                                <div class="stat-item">
                                    <div class="stat-icon wallet {{ (float) $userWallet->balance >= 0 ? 'positive' : 'negative' }}">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ number_format((float) $userWallet->balance, 2) }}</div>
                                        <div class="stat-label">{{ __('Wallet Balance') }} ({{ __('SAR') }})</div>
                                    </div>
                                </div>
                            @endif
                            @if ($userOrdersCount > 0)
                                <div class="stat-item">
                                    <div class="stat-icon average">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ number_format($userAverageOrder, 2) }}</div>
                                        <div class="stat-label">{{ __('Average Order') }} ({{ __('SAR') }})</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Transactions Card -->
            @if ($order->walletTransactions && $order->walletTransactions->count() > 0)
                <div class="info-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-wallet"></i>
                            {{ __('Payment Transactions') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="transactions-list">
                            @foreach ($order->walletTransactions as $transaction)
                                <div class="transaction-item">
                                    <div class="transaction-icon {{ $transaction->type }}">
                                        @if ($transaction->type === 'deposit')
                                            <i class="fas fa-arrow-down"></i>
                                        @else
                                            <i class="fas fa-arrow-up"></i>
                                        @endif
                                    </div>
                                    <div class="transaction-details">
                                        <div class="transaction-type">
                                            @if ($transaction->type === 'deposit')
                                                {{ __('Deposit') }}
                                            @elseif ($transaction->type === 'withdraw')
                                                {{ __('Withdraw') }}
                                            @else
                                                {{ ucfirst($transaction->type) }}
                                            @endif
                                        </div>
                                        <div class="transaction-amount">
                                            {{ number_format($transaction->amount, 2) }} {{ __('SAR') }}
                                        </div>
                                        @if ($transaction->note)
                                            <div class="transaction-note">
                                                <i class="fas fa-comment"></i>
                                                {{ $transaction->note }}
                                            </div>
                                        @endif
                                        <div class="transaction-date">
                                            <i class="fas fa-calendar"></i>
                                            {{ $transaction->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                    <div class="transaction-balance">
                                        <div class="balance-label">{{ __('Balance') }}</div>
                                        <div class="balance-value">{{ number_format($transaction->new_balance, 2) }} {{ __('SAR') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Items Section -->
        <div class="info-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Order Items') }}
                    <span class="items-count">({{ $order->items->count() }})</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="order-items-list">
                    @forelse($order->items as $item)
                        @php
                            $itemProduct = $item->product ?? null;
                            $itemMedia = $itemProduct ? $itemProduct->getFirstMedia('product_images') : null;
                            $itemPrice = ($itemProduct && isset($itemProduct->price)) ? (float) $itemProduct->price : 0;
                            $itemQuantity = (int) ($item->quantity ?? 1);
                            $itemTotal = $itemQuantity * $itemPrice;
                        @endphp
                        <div class="order-item-card">
                            <div class="item-image-wrapper">
                                @if ($itemMedia)
                                    <img src="{{ route('dashboard.product.image', $itemMedia->id) }}"
                                        alt="{{ $itemProduct->name ?? __('Product') }}" class="item-image"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="item-image-placeholder" style="display: none;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @else
                                    <div class="item-image-placeholder">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="item-details">
                                <h4 class="item-name">
                                    @if ($itemProduct && $itemProduct->name)
                                        @if (is_array($itemProduct->name))
                                            {{ $itemProduct->name[app()->getLocale()] ?? reset($itemProduct->name) }}
                                        @else
                                            {{ $itemProduct->name }}
                                        @endif
                                    @else
                                        {{ __('Product') }}
                                    @endif
                                </h4>
                                <div class="item-meta">
                                    <span class="item-meta-badge">
                                        <i class="fas fa-tag"></i>
                                        {{ __('Product ID') }}: #{{ $itemProduct->id ?? __('N/A') }}
                                    </span>
                                    @if (isset($itemProduct->price) && $itemProduct->price > 0)
                                        <span class="item-meta-badge">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ __('Unit Price') }}: {{ number_format($itemProduct->price, 2) }} {{ __('SAR') }}
                                        </span>
                                    @endif
                                </div>
                                @if ($item->player_id || $item->delivery_email || $item->activation_code)
                                    <div class="item-digital-info">
                                        @if ($item->player_id)
                                            <div class="digital-info-item">
                                                <i class="fas fa-user-circle"></i>
                                                <span><strong>{{ __('Player ID') }}:</strong> {{ $item->player_id }}</span>
                                            </div>
                                        @endif
                                        @if ($item->delivery_email)
                                            <div class="digital-info-item">
                                                <i class="fas fa-envelope"></i>
                                                <span><strong>{{ __('Delivery Email') }}:</strong> {{ $item->delivery_email }}</span>
                                            </div>
                                        @endif
                                        @if ($item->activation_code)
                                            <div class="digital-info-item">
                                                <i class="fas fa-key"></i>
                                                <span><strong>{{ __('Activation Code') }}:</strong> 
                                                    <code class="activation-code">{{ $item->activation_code }}</code>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="item-quantity-section">
                                <div class="quantity-badge">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('Quantity') }}: <strong>{{ $item->quantity }}</strong></span>
                                </div>
                            </div>
                            <div class="item-total-section">
                                <div class="total-amount-badge">
                                    <span class="total-label">{{ __('Item Total') }}</span>
                                    <span class="total-value">{{ number_format($itemTotal, 2) }} {{ __('SAR') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="no-items">
                            <i class="fas fa-inbox"></i>
                            <p>{{ __('No items found in this order') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="info-card summary-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calculator"></i>
                    {{ __('Order Summary') }}
                </h3>
            </div>
            <div class="card-body">
                @php
                    $subtotal = $order->items ? $order->items->sum(function ($item) {
                        $price = ($item->product && isset($item->product->price)) ? (float) $item->product->price : 0;
                        $quantity = (int) ($item->quantity ?? 1);
                        return $quantity * $price;
                    }) : 0;
                    $discount = 0;
                    $tax = 0;
                    $total = $order->total_amount && $order->total_amount > 0 
                        ? (float) $order->total_amount 
                        : (float) $subtotal;
                @endphp

                <div class="summary-list">
                    <div class="summary-row">
                        <span class="summary-label">
                            <i class="fas fa-shopping-bag"></i>
                            {{ __('Subtotal') }}
                        </span>
                        <span class="summary-value">{{ number_format($subtotal, 2) }} {{ __('SAR') }}</span>
                    </div>
                    @if ($discount > 0)
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-tag"></i>
                                {{ __('Discount') }}
                            </span>
                            <span class="summary-value discount">-{{ number_format($discount, 2) }} {{ __('SAR') }}</span>
                        </div>
                    @endif
                    @if ($tax > 0)
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-receipt"></i>
                                {{ __('Tax') }}
                            </span>
                            <span class="summary-value">{{ number_format($tax, 2) }} {{ __('SAR') }}</span>
                        </div>
                    @endif
                    <div class="summary-row total-row">
                        <span class="summary-label">
                            <i class="fas fa-money-bill-wave"></i>
                            {{ __('Total Amount') }}
                        </span>
                        <span class="summary-value total">{{ number_format($total, 2) }} {{ __('SAR') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="order-actions">
            <button class="action-btn primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('Print Invoice') }}
            </button>

            <form action="{{ route('dashboard.order.update', $order->id) }}" method="POST" class="order-action-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="action-btn success">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Complete Order') }}
                </button>
            </form>

            @php
                $canCancel = !in_array($order->status, ['canceled', 'cancelled']);
            @endphp

            @if ($canCancel)
                <form action="{{ route('dashboard.order.update', $order->id) }}" method="POST" class="order-action-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="canceled">
                    <input type="hidden" name="cancel_reason" value="{{ __('Customer request') }}">
                    <button type="submit" class="action-btn danger">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel Order') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <style>
        /* Order Detail Page Styles */
        .order-detail-container {
            padding: 2rem;
            background: var(--bg-secondary, #111827);
            min-height: 100vh;
            transition: background-color 0.3s ease;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1.5rem;
            background: var(--bg-primary, #1f2937);
            border-radius: 16px;
            border: 1px solid var(--border-color, #374151);
            box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.3));
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
        }

        .back-btn {
            background: var(--bg-secondary, #2d2d2d);
            color: var(--text-secondary, #cccccc);
            border: 1px solid var(--border-color, #404040);
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

        .back-btn:hover {
            background: #059669;
            color: white;
            border-color: #059669;
            transform: translateY(-2px);
        }

        .header-title {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            margin: 0;
            transition: color 0.3s ease;
        }

        .order-number {
            font-size: 1rem;
            color: #059669;
            font-weight: 600;
        }

        .header-status {
            display: flex;
            align-items: center;
        }

        .order-status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .order-status-badge.completed {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .order-status-badge.pending {
            background: rgba(139, 92, 246, 0.15);
            color: #8b5cf6;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .order-status-badge.processing {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .order-status-badge.cancelled,
        .order-status-badge.canceled {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Product Hero Section */
        .product-hero-section {
            background: var(--bg-primary, #1f2937);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color, #374151);
            box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.3));
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .product-image-container {
            flex-shrink: 0;
            width: 200px;
            height: 200px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-secondary, #111827);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--border-color, #374151);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary, #9ca3af);
            font-size: 3rem;
            gap: 1rem;
        }

        .product-image-placeholder span {
            font-size: 0.9rem;
            color: var(--text-secondary, #9ca3af);
        }

        .product-hero-info {
            flex: 1;
            min-width: 250px;
        }

        .product-hero-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            margin: 0 0 1rem 0;
            transition: color 0.3s ease;
        }

        .product-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .product-id-badge,
        .product-price-badge {
            padding: 0.5rem 1rem;
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid rgba(5, 150, 105, 0.3);
            border-radius: 8px;
            color: #059669;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Content Grid */
        .order-content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Info Cards */
        .info-card {
            background: var(--bg-primary, #1f2937);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.3));
            border: 1px solid var(--border-color, #374151);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg, 0 8px 15px rgba(0, 0, 0, 0.4));
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color, #374151);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary, #ffffff);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: color 0.3s ease;
        }

        .items-count {
            font-size: 0.9rem;
            color: var(--text-secondary, #9ca3af);
            font-weight: 400;
        }

        .card-body {
            padding-top: 0.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid rgba(5, 150, 105, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #059669;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-secondary, #9ca3af);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-primary, #ffffff);
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .cancel-reason {
            color: #ef4444;
            font-style: italic;
            word-break: break-word;
        }

        /* Customer Card */
        .customer-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: var(--bg-secondary, #111827);
            border-radius: 12px;
            border: 1px solid var(--border-color, #374151);
        }

        .customer-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.75rem;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        .customer-details {
            flex: 1;
        }

        .customer-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            margin: 0 0 0.75rem 0;
            transition: color 0.3s ease;
        }

        .customer-info-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .customer-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary, #9ca3af);
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .customer-info-item i {
            color: #059669;
            width: 16px;
        }

        /* Customer Statistics */
        .customer-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: var(--bg-secondary, #111827);
            border-radius: 12px;
            border: 1px solid var(--border-color, #374151);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.orders {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .stat-icon.spent {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .stat-icon.wallet.positive {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .stat-icon.wallet.negative {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .stat-icon.average {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            margin-bottom: 0.25rem;
            transition: color 0.3s ease;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary, #9ca3af);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status-badge.success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.warning {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Order Items */
        .order-items-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .order-item-card {
            display: grid;
            grid-template-columns: 100px 1fr auto auto;
            gap: 1.5rem;
            padding: 1.5rem;
            background: var(--bg-secondary, #111827);
            border-radius: 12px;
            border: 1px solid var(--border-color, #374151);
            transition: all 0.3s ease;
            align-items: center;
        }

        .order-item-card:hover {
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .item-image-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            background: var(--bg-primary, #1f2937);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color, #374151);
        }

        .item-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary, #9ca3af);
            font-size: 2rem;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .item-name {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary, #ffffff);
            transition: color 0.3s ease;
        }

        .item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .item-meta-badge {
            padding: 0.4rem 0.8rem;
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid rgba(5, 150, 105, 0.3);
            border-radius: 8px;
            color: #059669;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .item-digital-info {
            margin-top: 0.75rem;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
        }

        .digital-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            color: var(--text-secondary, #9ca3af);
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .digital-info-item:not(:last-child) {
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }

        .digital-info-item i {
            color: #3b82f6;
            width: 20px;
            text-align: center;
        }

        .digital-info-item strong {
            color: var(--text-primary, #ffffff);
            font-weight: 600;
            margin-left: 0.25rem;
        }

        .activation-code {
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid rgba(5, 150, 105, 0.3);
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #059669;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .item-quantity-section,
        .item-total-section {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-badge {
            padding: 0.75rem 1.25rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #3b82f6;
            font-weight: 600;
        }

        .total-amount-badge {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }

        .total-label {
            font-size: 0.85rem;
            color: var(--text-secondary, #9ca3af);
            transition: color 0.3s ease;
        }

        .total-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            transition: color 0.3s ease;
        }

        .no-items {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary, #9ca3af);
        }

        .no-items i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--text-secondary, #9ca3af);
        }

        /* Payment Transactions */
        .transactions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.25rem;
            background: var(--bg-secondary, #111827);
            border-radius: 12px;
            border: 1px solid var(--border-color, #374151);
            transition: all 0.3s ease;
        }

        .transaction-item:hover {
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .transaction-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .transaction-icon.deposit {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .transaction-icon.withdraw {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .transaction-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .transaction-type {
            font-size: 0.9rem;
            color: var(--text-secondary, #9ca3af);
            font-weight: 500;
            text-transform: capitalize;
            transition: color 0.3s ease;
        }

        .transaction-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
            transition: color 0.3s ease;
        }

        .transaction-note {
            font-size: 0.85rem;
            color: var(--text-secondary, #9ca3af);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-style: italic;
            transition: color 0.3s ease;
        }

        .transaction-note i {
            color: #059669;
        }

        .transaction-date {
            font-size: 0.8rem;
            color: var(--text-secondary, #9ca3af);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.3s ease;
        }

        .transaction-date i {
            color: #059669;
        }

        .transaction-balance {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }

        .balance-label {
            font-size: 0.85rem;
            color: var(--text-secondary, #9ca3af);
            transition: color 0.3s ease;
        }

        .balance-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #059669;
        }

        /* Summary Card */
        .summary-card {
            margin-bottom: 2rem;
        }

        .summary-list {
            padding: 0.5rem 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color, #374151);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary, #9ca3af);
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .summary-value {
            color: var(--text-primary, #ffffff);
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .summary-row.total-row {
            padding-top: 1.5rem;
            margin-top: 1rem;
            border-top: 2px solid #059669;
        }

        .summary-row.total-row .summary-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary, #ffffff);
        }

        .summary-row.total-row .summary-value.total {
            font-size: 1.75rem;
            font-weight: 700;
            color: #059669;
        }

        .summary-value.discount {
            color: #10b981;
        }

        /* Order Actions */
        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .order-action-form {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        .action-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            min-width: 180px;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn i {
            font-size: 1.1rem;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: 2px solid #059669;
        }

        .action-btn.primary:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        .action-btn.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .action-btn.success:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #10b981;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }

        .action-btn.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 2px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn.danger:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: #ef4444;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        /* Light Theme Styles */
        [data-theme="light"] .order-detail-container {
            background: #f9fafb;
        }

        [data-theme="light"] .page-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .page-title {
            color: #111827;
        }

        [data-theme="light"] .back-btn {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .back-btn:hover {
            background: #059669;
            color: #ffffff;
            border-color: #059669;
        }

        [data-theme="light"] .product-hero-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .product-image-container {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
        }

        [data-theme="light"] .product-hero-name {
            color: #111827;
        }

        [data-theme="light"] .info-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .card-header {
            border-bottom: 1px solid #e5e7eb;
        }

        [data-theme="light"] .card-title {
            color: #111827;
        }

        [data-theme="light"] .info-label {
            color: #6b7280;
        }

        [data-theme="light"] .info-value {
            color: #111827;
        }

        [data-theme="light"] .customer-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .customer-name {
            color: #111827;
        }

        [data-theme="light"] .customer-info-item {
            color: #6b7280;
        }

        [data-theme="light"] .item-digital-info {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .digital-info-item {
            color: #6b7280;
        }

        [data-theme="light"] .digital-info-item strong {
            color: #111827;
        }

        [data-theme="light"] .transaction-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .transaction-type {
            color: #6b7280;
        }

        [data-theme="light"] .transaction-amount {
            color: #111827;
        }

        [data-theme="light"] .transaction-note {
            color: #6b7280;
        }

        [data-theme="light"] .transaction-date {
            color: #6b7280;
        }

        [data-theme="light"] .balance-label {
            color: #6b7280;
        }

        [data-theme="light"] .stat-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .stat-value {
            color: #111827;
        }

        [data-theme="light"] .stat-label {
            color: #6b7280;
        }

        [data-theme="light"] .order-item-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        [data-theme="light"] .item-name {
            color: #111827;
        }

        [data-theme="light"] .total-label {
            color: #6b7280;
        }

        [data-theme="light"] .total-value {
            color: #111827;
        }

        [data-theme="light"] .summary-row {
            border-bottom: 1px solid #e5e7eb;
        }

        [data-theme="light"] .summary-label {
            color: #6b7280;
        }

        [data-theme="light"] .summary-value {
            color: #111827;
        }

        [data-theme="light"] .summary-row.total-row .summary-label {
            color: #111827;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .order-content-grid {
                grid-template-columns: 1fr;
            }

            .order-item-card {
                grid-template-columns: 80px 1fr;
                gap: 1rem;
            }

            .item-quantity-section,
            .item-total-section {
                grid-column: 2;
                margin-top: 0.5rem;
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .order-detail-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .product-hero-section {
                flex-direction: column;
                text-align: center;
            }

            .product-image-container {
                width: 150px;
                height: 150px;
                margin: 0 auto;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .action-btn {
                min-width: 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script>
        // Show and auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    notification.classList.add('hide');
                    setTimeout(() => {
                        notification.remove();
                    }, 400);
                }, 5000);

                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 5s linear';
                }
            });
        });
    </script>
@endpush
