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
                        <span class="info-label">{{ __('Order ID') }}</span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('Order Date') }}</span>
                        <span class="info-value">{{ $order->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('Order Time') }}</span>
                        <span class="info-value">{{ $order->created_at->format('H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('Payment Method') }}</span>
                        <span class="info-value">{{ __('Electronic Wallet') }}</span>
                    </div>
                </div>

                <div class="customer-info">
                    <div class="customer-avatar">{{ substr($order->user->name ?? 'U', 0, 1) }}</div>
                    <div class="customer-details">
                        <h6>{{ $order->user->name ?? __('Guest') }}</h6>
                        <p>{{ $order->user->email ?? __('No email') }}</p>
                    </div>
                </div>

            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h4 class="summary-title">{{ __('Order Summary') }}</h4>

                <div class="summary-row">
                    <span class="summary-label">{{ __('Total') }}</span>
                    <span class="summary-value">{{ number_format($order->total_amount, 2) }} {{ __('SAR') }}</span>
                </div>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="order-actions">
            <button class="action-btn primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('Print Invoice') }}
            </button>
            <a href="{{ route('dashboard.order.edit', $order->id) }}" class="action-btn secondary">
                <i class="fas fa-edit"></i>
                {{ __('Edit Order') }}
            </a>
            <button class="action-btn secondary" onclick="updateDeliveryStatus()">
                <i class="fas fa-truck"></i>
                {{ __('Update Delivery Status') }}
            </button>
            <button class="action-btn danger" onclick="cancelOrder()">
                <i class="fas fa-times"></i>
                {{ __('Cancel Order') }}
            </button>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div id="cancelOrderModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('Cancel Order') }}</h3>
                <button class="modal-close" onclick="closeCancelModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="cancel-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>{{ __('Are you sure you want to cancel this order?') }}</p>
                    <p class="order-info">{{ __('Order') }} #{{ $order->id }} {{ __('for customer') }}
                        {{ $order->user->name }}</p>
                    <p class="warning-text">{{ __('This action cannot be undone.') }}</p>
                </div>
                <form id="cancelOrderForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="cancel_reason" class="form-label">{{ __('Cancellation Reason') }}</label>
                        <textarea name="cancel_reason" id="cancel_reason" class="form-textarea" rows="3"
                            placeholder="{{ __('Please provide a reason for cancellation...') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeCancelModal()">{{ __('Cancel') }}</button>
                <button type="button" class="btn-danger" onclick="confirmCancelOrder()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel Order') }}
                </button>
            </div>
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
            grid-template-columns: 2fr 1fr;
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

        function updateDeliveryStatus() {
            // Implement update delivery status functionality
            console.log('Update delivery status');
            alert('{{ __('Update delivery status functionality will be implemented') }}');
        }

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
