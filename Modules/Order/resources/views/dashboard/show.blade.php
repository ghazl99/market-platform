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
        <div class="page-header">
            <h1 class="page-title">{{ __('Order Details') }} #{{ $order->id }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.order.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>

        <!-- Order Information Cards -->
        <div class="order-info-grid">
            <!-- Order Details -->
            <div class="order-details-card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Order Information') }}</h3>
                    <span class="order-status {{ $order->status }}">
                        @switch($order->status)
                            @case('completed')
                                {{ __('Completed') }}
                            @break

                            @case('pending')
                                {{ __('Pending') }}
                            @break

                            @case('processing')
                                {{ __('Processing') }}
                            @break

                            @case('canceled')
                                {{ __('Cancelled') }}
                            @break

                            @case('cancelled')
                                {{ __('Cancelled') }}
                            @break

                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </span>
                </div>

                <div class="order-info">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-hashtag"></i>
                            {{ __('Order ID') }}
                        </span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            {{ __('Order Date') }}
                        </span>
                        <span class="info-value">{{ $order->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-clock"></i>
                            {{ __('Order Time') }}
                        </span>
                        <span class="info-value">{{ $order->created_at->format('H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-credit-card"></i>
                            {{ __('Payment Method') }}
                        </span>
                        <span class="info-value">{{ __('Electronic Wallet') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-money-check-alt"></i>
                            {{ __('Payment Status') }}
                        </span>
                        <span class="info-value">
                            @if ($order->payment_status === 'paid')
                                <span class="badge success">{{ __('Paid') }}</span>
                            @else
                                <span class="badge warning">{{ __('Unpaid') }}</span>
                            @endif
                        </span>
                    </div>
                    @if ($order->cancel_reason)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ __('Cancellation Reason') }}
                            </span>
                            <span class="info-value cancel-reason">{{ $order->cancel_reason }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="order-details-card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Customer Information') }}</h3>
                </div>

                <div class="customer-info">
                    <div class="customer-avatar">{{ substr($order->user->name ?? 'U', 0, 1) }}</div>
                    <div class="customer-details">
                        <h6>{{ $order->user->name ?? __('Guest') }}</h6>
                        <p>
                            <i class="fas fa-envelope"></i>
                            {{ $order->user->email ?? __('No email') }}
                        </p>
                        @if ($order->user)
                            <p>
                                <i class="fas fa-id-card"></i>
                                {{ __('User ID') }}: #{{ $order->user->id }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-items-section">
            <div class="order-details-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('Order Items') }} ({{ $order->items->count() }})
                    </h3>
                </div>

                <div class="order-items-list">
                    @forelse($order->items as $item)
                        @php
                            $product = $item->product;
                            $productMedia = $product->media->first();
                            $itemTotal = $item->quantity * ($product->price ?? 0);
                        @endphp
                        <div class="order-item-card">
                            <div class="item-image-wrapper">
                                @if ($productMedia)
                                    <img src="{{ route('dashboard.product.image', $productMedia->id) }}"
                                        alt="{{ $product->name }}" class="item-image"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="item-image-placeholder" style="display: none;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @else
                                    <div class="item-image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="item-details">
                                <h4 class="item-name">{{ $product->name ?? __('Product') }}</h4>
                                <div class="item-meta">
                                    <span class="item-id">
                                        <i class="fas fa-tag"></i>
                                        {{ __('Product ID') }}: #{{ $product->id ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="item-price-info">
                                    <span class="item-unit-price">
                                        {{ __('Unit Price') }}: {{ number_format($product->price ?? 0, 2) }}
                                        {{ __('SAR') }}
                                    </span>
                                </div>
                            </div>
                            <div class="item-quantity">
                                <div class="quantity-badge">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('Quantity') }}: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="item-total">
                                <div class="total-amount">
                                    <span class="total-label">{{ __('Item Total') }}</span>
                                    <span class="total-value">{{ number_format($itemTotal, 2) }}
                                        {{ __('SAR') }}</span>
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

        <!-- Order Summary -->
        <div class="order-summary-section">
            <div class="order-details-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i>
                        {{ __('Order Summary') }}
                    </h3>
                </div>

                <div class="summary-content">
                    @php
                        $subtotal = $order->items->sum(function ($item) {
                            return $item->quantity * ($item->product->price ?? 0);
                        });
                        $discount = 0; // يمكن إضافته لاحقاً
                        $tax = 0; // يمكن إضافته لاحقاً
                        $total = $order->total_amount;
                    @endphp

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
                            <span class="summary-value discount">-{{ number_format($discount, 2) }}
                                {{ __('SAR') }}</span>
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
                        <span class="summary-value total-amount">{{ number_format($total, 2) }}
                            {{ __('SAR') }}</span>
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

            {{-- Complete Order Button --}}
            {{-- Debug: Status is "{{ $order->status }}" --}}
            <form action="{{ route('dashboard.order.update', $order->id) }}" method="POST" class="order-action-form"
                style="display: inline-block !important; visibility: visible !important;">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="action-btn success"
                    style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Complete Order') }}
                </button>
            </form>

            {{-- Cancel Order Button - Show if order is not canceled or cancelled --}}
            @php
                $canCancel = !in_array($order->status, ['canceled', 'cancelled']);
            @endphp

            @if ($canCancel)
                <form action="{{ route('dashboard.order.update', $order->id) }}" method="POST"
                    class="order-action-form">
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
        /* Order Detail Page Specific Styles */
        .order-detail-container {
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .back-btn {
            background: #2d2d2d;
            color: #cccccc;
            border: 1px solid #404040;
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
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .order-details-card {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #404040;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333333;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .order-status.completed {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .order-status.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .order-status.processing {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .order-status.cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #cccccc;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #1a1a1a;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .customer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .customer-details h6 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
        }

        .customer-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #cccccc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .cancel-reason {
            color: #ef4444;
            font-style: italic;
            word-break: break-word;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge.success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge.warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        /* Order Items Section */
        .order-items-section {
            margin-bottom: 2rem;
        }

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
            background: #1a1a1a;
            border-radius: 12px;
            border: 1px solid #333333;
            transition: all 0.3s ease;
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
            background: #2d2d2d;
            display: flex;
            align-items: center;
            justify-content: center;
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
            color: #666666;
            font-size: 2rem;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-name {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .item-id {
            font-size: 0.85rem;
            color: #cccccc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-price-info {
            margin-top: 0.5rem;
        }

        .item-unit-price {
            font-size: 0.9rem;
            color: #059669;
            font-weight: 600;
        }

        .item-quantity {
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

        .item-total {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .total-amount {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .total-label {
            font-size: 0.85rem;
            color: #cccccc;
            margin-bottom: 0.25rem;
        }

        .total-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
        }

        .no-items {
            text-align: center;
            padding: 3rem;
            color: #cccccc;
        }

        .no-items i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #666666;
        }

        /* Order Summary Section */
        .order-summary-section {
            margin-bottom: 2rem;
        }

        .summary-content {
            padding: 1rem 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #333333;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #cccccc;
            font-size: 0.95rem;
        }

        .summary-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-row.total-row {
            padding-top: 1.5rem;
            margin-top: 1rem;
            border-top: 2px solid #059669;
        }

        .summary-row.total-row .summary-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
        }

        .summary-row.total-row .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #059669;
        }

        .summary-value.discount {
            color: #10b981;
        }

        .order-summary {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .summary-title {
            margin-bottom: 1.5rem;
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #333333;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: #ffffff;
        }

        .summary-label {
            color: #cccccc;
        }

        .summary-value {
            color: #ffffff;
            font-weight: 600;
        }

        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .order-action-form {
            display: inline-block !important;
            margin: 0 !important;
            padding: 0 !important;
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

        .action-btn.secondary {
            background: #2d2d2d;
            color: #ffffff;
            border: 2px solid #404040;
        }

        .action-btn.secondary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
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

        .action-btn.success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border: 2px solid rgba(16, 185, 129, 0.3) !important;
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .action-btn.success:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            border-color: #10b981 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }

        /* Cancel Order Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-container {
            background: #2d2d2d;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #404040;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            color: #cccccc;
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

        .modal-close:hover {
            background: #404040;
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .cancel-warning {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .cancel-warning i {
            font-size: 2rem;
            color: #ef4444;
            margin-bottom: 0.5rem;
            display: block;
        }

        .cancel-warning p {
            margin: 0.5rem 0;
            color: #ffffff;
        }

        .order-info {
            font-weight: 600;
            color: #059669 !important;
        }

        .warning-text {
            font-size: 0.9rem;
            color: #cccccc !important;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            color: #cccccc;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            background: #1a1a1a;
            border: 1px solid #404040;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.9rem;
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-textarea::placeholder {
            color: #666666;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-top: 1px solid #404040;
            justify-content: flex-end;
        }

        .btn-secondary,
        .btn-danger {
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
            background: #404040;
            color: #cccccc;
        }

        .btn-secondary:hover {
            background: #555555;
            color: #ffffff;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:disabled {
            background: #666666;
            color: #999999;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .order-info-grid {
                grid-template-columns: 1fr;
            }

            .order-item-card {
                grid-template-columns: 80px 1fr;
                gap: 1rem;
            }

            .item-quantity,
            .item-total {
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

            .page-actions {
                justify-content: space-between;
            }

            .order-info {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
                align-items: center;
            }

            .action-btn {
                min-width: 100%;
                max-width: 300px;
            }
        }

        /* Light Mode Styles - Maximum Priority */
        html[data-theme="light"] .order-detail-container,
        html[data-theme="light"] body .order-detail-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .back-btn,
        html[data-theme="light"] body .back-btn {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .back-btn:hover,
        html[data-theme="light"] body .back-btn:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .order-details-card,
        html[data-theme="light"] body .order-details-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .card-header,
        html[data-theme="light"] body .card-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .card-title,
        html[data-theme="light"] body .card-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .info-label,
        html[data-theme="light"] body .info-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .info-value,
        html[data-theme="light"] body .info-value {
            color: #111827 !important;
        }

        html[data-theme="light"] .customer-info,
        html[data-theme="light"] body .customer-info {
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .customer-details h6,
        html[data-theme="light"] body .customer-details h6 {
            color: #111827 !important;
        }

        html[data-theme="light"] .customer-details p,
        html[data-theme="light"] body .customer-details p {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .order-summary,
        html[data-theme="light"] body .order-summary {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .order-summary h4,
        html[data-theme="light"] .summary-title,
        html[data-theme="light"] body .order-summary h4,
        html[data-theme="light"] body .summary-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .summary-row,
        html[data-theme="light"] body .summary-row {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .summary-label,
        html[data-theme="light"] body .summary-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .summary-value,
        html[data-theme="light"] body .summary-value {
            color: #111827 !important;
        }

        html[data-theme="light"] .action-btn.secondary,
        html[data-theme="light"] body .action-btn.secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 2px solid #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.secondary:hover,
        html[data-theme="light"] body .action-btn.secondary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .action-btn.danger,
        html[data-theme="light"] body .action-btn.danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: 2px solid rgba(239, 68, 68, 0.3) !important;
        }

        html[data-theme="light"] .action-btn.danger:hover,
        html[data-theme="light"] body .action-btn.danger:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border-color: #ef4444 !important;
        }

        html[data-theme="light"] .action-btn.success,
        html[data-theme="light"] body .action-btn.success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border: 2px solid rgba(16, 185, 129, 0.3) !important;
        }

        html[data-theme="light"] .action-btn.success:hover,
        html[data-theme="light"] body .action-btn.success:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border-color: #10b981 !important;
        }

        html[data-theme="light"] .modal-container,
        html[data-theme="light"] body .modal-container {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .modal-header,
        html[data-theme="light"] body .modal-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .modal-title,
        html[data-theme="light"] body .modal-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .modal-close,
        html[data-theme="light"] body .modal-close {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .modal-close:hover,
        html[data-theme="light"] body .modal-close:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .cancel-warning,
        html[data-theme="light"] body .cancel-warning {
            background: rgba(239, 68, 68, 0.1) !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
        }

        html[data-theme="light"] .cancel-warning p,
        html[data-theme="light"] body .cancel-warning p {
            color: #111827 !important;
        }

        html[data-theme="light"] .cancel-warning .order-info,
        html[data-theme="light"] body .cancel-warning .order-info {
            color: #059669 !important;
        }

        html[data-theme="light"] .cancel-warning .warning-text,
        html[data-theme="light"] body .cancel-warning .warning-text {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-textarea,
        html[data-theme="light"] body .form-textarea {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-textarea:focus,
        html[data-theme="light"] body .form-textarea:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-textarea::placeholder,
        html[data-theme="light"] body .form-textarea::placeholder {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .modal-footer,
        html[data-theme="light"] body .modal-footer {
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .btn-secondary,
        html[data-theme="light"] body .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .btn-secondary:hover,
        html[data-theme="light"] body .btn-secondary:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .notification,
        html[data-theme="light"] body .notification {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .notification-title,
        html[data-theme="light"] body .notification-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .notification-message,
        html[data-theme="light"] body .notification-message {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .notification-close,
        html[data-theme="light"] body .notification-close {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .notification-close:hover,
        html[data-theme="light"] body .notification-close:hover {
            color: #374151 !important;
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script>
        // Ensure theme is applied
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                // Force reflow to apply styles
                document.body.offsetHeight;
                // Re-apply theme to ensure styles are loaded
                document.documentElement.setAttribute('data-theme', 'light');
            }
        });

        // Show and auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
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
            });
        });


        function cancelOrder() {
            document.getElementById('cancelOrderModal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancelOrderModal').style.display = 'none';
            document.getElementById('cancel_reason').value = '';
        }

        function confirmCancelOrder() {
            const reason = document.getElementById('cancel_reason').value.trim();
            const cancelBtn = document.querySelector('.btn-danger');

            if (!reason) {
                alert('{{ __('Please provide a reason for cancellation') }}');
                return;
            }

            // Disable button and show loading
            cancelBtn.disabled = true;
            cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Cancelling...') }}';

            // Send cancellation request
            fetch('{{ route('dashboard.order.update', $order->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: 'cancelled',
                        cancel_reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification('error', data.message);
                        // Re-enable button
                        cancelBtn.disabled = false;
                        cancelBtn.innerHTML = '<i class="fas fa-times"></i> {{ __('Cancel Order') }}';
                    }
                })
                .catch(error => {
                    showNotification('error', '{{ __('An error occurred while cancelling the order') }}');
                    // Re-enable button
                    cancelBtn.disabled = false;
                    cancelBtn.innerHTML = '<i class="fas fa-times"></i> {{ __('Cancel Order') }}';
                });
        }

        // Close modal when clicking outside
        document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
@endpush
