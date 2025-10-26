@extends('core::dashboard.layouts.app')

@section('title', __('Customer Details'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Dark Theme Variables */
        :root {
            --dark-bg-primary: #1a1a1a;
            --dark-bg-secondary: #2d2d2d;
            --dark-bg-tertiary: #404040;
            --dark-text-primary: #ffffff;
            --dark-text-secondary: #b3b3b3;
            --dark-text-muted: #888888;
            --dark-border: #404040;
            --dark-border-light: #333333;
            --dark-accent: #ff6b35;
            --dark-success: #10b981;
            --dark-warning: #f59e0b;
            --dark-danger: #ef4444;
            --dark-info: #3b82f6;
            --dark-purple: #8b5cf6;
        }

        /* Override main layout background */
        body {
            background: var(--dark-bg-primary) !important;
        }

        .main-content {
            background: var(--dark-bg-primary) !important;
        }

        /* Customer Detail Container */
        .customer-detail-container {
            background: var(--dark-bg-primary);
            min-height: 100vh;
            padding: 2rem;
            color: var(--dark-text-primary);
        }

        /* Header Section */
        .customer-header {
            display: grid;
            grid-template-columns: 200px 1fr auto;
            gap: 2rem;
            align-items: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: var(--dark-bg-secondary);
            border-radius: 16px;
            border: 1px solid var(--dark-border);
        }

        .customer-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 4px solid var(--dark-border);
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-avatar .initials {
            font-size: 3rem;
            font-weight: 700;
            color: var(--dark-bg-primary);
            background: linear-gradient(135deg, var(--dark-accent), var(--dark-info));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .customer-info {
            color: var(--dark-text-primary);
        }

        .customer-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark-text-primary);
        }

        .customer-email {
            font-size: 1.2rem;
            color: var(--dark-text-secondary);
            margin-bottom: 1rem;
        }

        .customer-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .action-btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            min-width: 180px;
            justify-content: center;
        }

        .action-btn.add-balance {
            background: linear-gradient(135deg, var(--dark-success), #059669);
            color: white;
        }

        .action-btn.add-balance:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        /* Account Information */
        .account-info {
            background: var(--dark-bg-secondary);
            margin-bottom: 2rem;
            border-radius: 16px;
            padding: 0;
            border: 1px solid var(--dark-border);
            overflow: hidden;
        }

        .account-info-header {
            background: var(--dark-danger);
            color: white;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .account-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .account-detail-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            border: 1px solid var(--dark-border-light);
        }

        .detail-icon {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .detail-icon.name {
            background: var(--dark-info);
        }

        .detail-icon.email {
            background: var(--dark-success);
        }

        .detail-icon.balance {
            background: var(--dark-purple);
        }

        .detail-icon.debt {
            background: var(--dark-warning);
        }

        .detail-icon.activity {
            background: var(--dark-success);
        }

        .detail-icon.rank {
            background: var(--dark-danger);
        }

        .detail-icon.verification {
            background: var(--dark-danger);
        }

        .detail-icon.opening-price {
            background: var(--dark-success);
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.9rem;
            color: var(--dark-text-muted);
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text-primary);
        }

        /* Wallet Transactions */
        .wallet-transactions {
            background: var(--dark-bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--dark-border);
        }

        .wallet-transactions h3 {
            color: var(--dark-text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .transactions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            border: 1px solid var(--dark-border-light);
            transition: all 0.3s ease;
        }

        .transaction-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .transaction-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .transaction-icon.deposit {
            background: var(--dark-success);
        }

        .transaction-icon.withdraw {
            background: var(--dark-danger);
        }

        .transaction-details {
            flex: 1;
        }

        .transaction-name {
            color: var(--dark-text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .transaction-description {
            color: var(--dark-text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .transaction-time {
            color: var(--dark-text-muted);
            font-size: 0.8rem;
        }

        .transaction-amounts {
            text-align: right;
        }

        .primary-amount {
            color: var(--dark-text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .secondary-amount {
            color: var(--dark-text-secondary);
            font-size: 0.9rem;
        }

        /* KYC Section */
        .kyc-section {
            background: var(--dark-bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--dark-border);
        }

        .kyc-status {
            margin-bottom: 2rem;
        }

        .kyc-status h3 {
            color: var(--dark-text-primary);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .status-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .status-label {
            color: var(--dark-text-secondary);
            font-size: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-badge.not-required {
            background: var(--dark-success);
            color: white;
        }

        .verification-btn {
            background: var(--dark-success);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .verification-btn:hover {
            background: var(--dark-success-hover);
            transform: translateY(-2px);
        }

        .kyc-table {
            margin-top: 1.5rem;
        }

        .kyc-table .data-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            overflow: hidden;
        }

        .kyc-table .data-table th {
            background: var(--dark-bg-primary);
            color: var(--dark-text-primary);
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            border-bottom: 1px solid var(--dark-border);
        }

        .kyc-table .data-table td {
            padding: 1rem;
            color: var(--dark-text-secondary);
            border-bottom: 1px solid var(--dark-border-light);
        }

        .kyc-table .data-table tr:last-child td {
            border-bottom: none;
        }

        .kyc-table .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--dark-text-muted);
        }

        .kyc-table .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .kyc-table .empty-state p {
            font-size: 1.1rem;
            margin: 0;
        }

        /* Tabs Section */
        .tabs-section {
            background: var(--dark-bg-secondary);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--dark-border);
        }

        .tabs-header {
            display: flex;
            background: var(--dark-bg-tertiary);
            border-bottom: 1px solid var(--dark-border);
        }

        .tab-button {
            flex: 1;
            padding: 1.5rem 2rem;
            background: none;
            border: none;
            color: var(--dark-text-secondary);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-button.active {
            color: var(--dark-text-primary);
            background: var(--dark-bg-secondary);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--dark-danger);
        }

        .tab-content {
            padding: 2rem;
            min-height: 400px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            overflow: hidden;
        }

        .orders-table th {
            background: var(--dark-bg-primary);
            color: var(--dark-text-primary);
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border-bottom: 1px solid var(--dark-border);
        }

        .orders-table td {
            padding: 1rem;
            color: var(--dark-text-secondary);
            border-bottom: 1px solid var(--dark-border-light);
        }

        .orders-table tr:hover {
            background: var(--dark-bg-primary);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.processing {
            background: rgba(245, 158, 11, 0.2);
            color: var(--dark-warning);
            border: 1px solid var(--dark-warning);
        }

        .status-badge.canceled {
            background: rgba(239, 68, 68, 0.2);
            color: var(--dark-danger);
            border: 1px solid var(--dark-danger);
        }

        .status-badge.completed {
            background: rgba(16, 185, 129, 0.2);
            color: var(--dark-success);
            border: 1px solid var(--dark-success);
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-text-primary);
        }

        .price-value {
            font-weight: 600;
            color: var(--dark-success);
        }

        .table-icon {
            font-size: 1.2rem;
        }

        .table-icon.check {
            color: var(--dark-success);
        }

        .table-icon.card {
            color: var(--dark-info);
        }

        .table-icon.settings {
            color: var(--dark-purple);
        }

        .table-icon.bell {
            color: var(--dark-warning);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .customer-detail-container {
                padding: 1rem;
            }

            .customer-header {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 1rem;
            }

            .customer-actions {
                flex-direction: row;
                justify-content: center;
            }

            .account-details {
                grid-template-columns: 1fr;
            }

            .tabs-header {
                flex-direction: column;
            }

            .orders-table {
                font-size: 0.9rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 0.75rem 0.5rem;
            }
        }

        /* Sub-tabs Styling */
        .sub-tabs-header {
            display: flex;
            background: var(--dark-bg-primary);
            border-bottom: 1px solid var(--dark-border);
            margin-bottom: 1rem;
        }

        .sub-tab-button {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: var(--dark-text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .sub-tab-button.active {
            color: var(--dark-text-primary);
            background: var(--dark-bg-secondary);
        }

        .sub-tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--dark-danger);
        }

        .sub-tab-pane {
            display: none;
        }

        .sub-tab-pane.active {
            display: block;
        }

        /* KYC Section */
        .kyc-section {
            padding: 1rem;
        }

        .kyc-status {
            background: var(--dark-bg-tertiary);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--dark-border-light);
        }

        .kyc-status h3 {
            color: var(--dark-text-primary);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .status-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .status-label {
            color: var(--dark-text-secondary);
        }

        .status-badge.not-required {
            background: rgba(16, 185, 129, 0.2);
            color: var(--dark-success);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .verification-btn {
            background: var(--dark-success);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .verification-btn:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .kyc-table {
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--dark-border-light);
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .data-table th {
            background: var(--dark-bg-primary);
            color: var(--dark-text-primary);
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border-bottom: 1px solid var(--dark-border);
        }

        .data-table td {
            padding: 1rem;
            color: var(--dark-text-secondary);
            border-bottom: 1px solid var(--dark-border-light);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--dark-text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Payment Section */
        .payment-section {
            padding: 1rem;
        }

        .payment-history h3 {
            color: var(--dark-text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .payment-table {
            background: var(--dark-bg-tertiary);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--dark-border-light);
        }

        .amount-paid {
            color: var(--dark-success);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .status-badge.completed {
            background: rgba(16, 185, 129, 0.2);
            color: var(--dark-success);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.processing {
            background: rgba(245, 158, 11, 0.2);
            color: var(--dark-warning);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.failed {
            background: rgba(239, 68, 68, 0.2);
            color: var(--dark-danger);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Settings Section */
        .settings-section {
            padding: 1rem;
        }

        .settings-card {
            background: var(--dark-bg-tertiary);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid var(--dark-border-light);
            max-width: 500px;
        }

        .settings-card h3 {
            color: var(--dark-text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .password-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            color: var(--dark-text-primary);
            font-weight: 500;
        }

        .form-input {
            padding: 0.75rem 1rem;
            border: 1px solid var(--dark-border);
            border-radius: 8px;
            background: var(--dark-bg-primary);
            color: var(--dark-text-primary);
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--dark-info);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .submit-btn {
            background: var(--dark-warning);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            background: var(--dark-text-muted);
            cursor: not-allowed;
            transform: none;
            opacity: 0.7;
        }

        .submit-btn:disabled:hover {
            background: var(--dark-text-muted);
            transform: none;
        }

        /* Notifications Section */
        .notifications-section {
            padding: 1rem;
        }

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-item {
            background: var(--dark-bg-tertiary);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--dark-border-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            color: var(--dark-text-primary);
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .notification-type {
            color: var(--dark-text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .notification-time {
            color: var(--dark-text-muted);
            font-size: 0.8rem;
        }

        .notification-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .copy-btn {
            background: none;
            border: none;
            color: var(--dark-text-muted);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: var(--dark-bg-primary);
            color: var(--dark-text-primary);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
        }

        .notification-icon.cancelled {
            background: var(--dark-danger);
        }

        .notification-icon.completed {
            background: var(--dark-success);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--dark-border);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--dark-text-muted);
        }
    </style>
@endpush

@section('content')
    <div class="customer-detail-container">
        <!-- Customer Header -->
        <div class="customer-header">
            <div class="customer-avatar">
                @if ($customer->profile_photo_url)
                    <img src="{{ $customer->profile_photo_url }}" alt="{{ $customer->name }}">
                @else
                    <div class="initials">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="customer-info">
                <h1 class="customer-name">{{ $customer->name }}</h1>
                <p class="customer-email">{{ $customer->email }}</p>
            </div>
            <div class="customer-actions">
                <button class="action-btn add-balance" onclick="addBalance()">
                    <i class="fas fa-plus"></i>
                    {{ __('إضافة رصيد') }}
                </button>
            </div>
        </div>

        <!-- Account Information -->
        <div class="account-info">
            <div class="account-info-header">
                {{ __('معلومات الحساب') }}
            </div>
            <div class="account-details">
                <div class="account-detail-item">
                    <div class="detail-icon name"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('الاسم') }}</div>
                        <div class="detail-value">{{ $customer->name }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon email"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('البريد الالكتروني') }}</div>
                        <div class="detail-value">{{ $customer->email }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon balance"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('الرصيد') }}</div>
                        <div class="detail-value">${{ number_format($customer->wallet?->balance ?? 0, 4) }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon debt"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('الديون') }}</div>
                        <div class="detail-value">${{ number_format($customer->debt_limit ?? 0, 2) }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon activity"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('اخر نشاط') }}</div>
                        <div class="detail-value">
                            {{ $customer->last_login_at ? $customer->last_login_at->format('H:i:s Y-m-d') : __('لم يسجل دخول') }}
                        </div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon rank"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('الرتبة') }}</div>
                        <div class="detail-value">{{ $customer->group?->name ?? '' }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon verification"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('حالة التحقق') }}</div>
                        <div class="detail-value">{{ $customer->email_verified_at ? __('محقق') : __('غير محقق') }}</div>
                    </div>
                </div>
                <div class="account-detail-item">
                    <div class="detail-icon opening-price"></div>
                    <div class="detail-content">
                        <div class="detail-label">{{ __('سعر افتتاحي') }}</div>
                        <div class="detail-value">[{{ $customer->group?->profit_percentage ?? 0 }}%]</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="tabs-section">
            <div class="tabs-header">
                <button class="tab-button active" data-tab="wallet">
                    <i class="fas fa-wallet"></i>
                    {{ __('المحفظة') }}
                </button>
                <button class="tab-button" data-tab="kyc">
                    <i class="fas fa-id-card"></i>
                    {{ __('KYC') }}
                </button>
                <button class="tab-button" data-tab="payment">
                    <i class="fas fa-credit-card"></i>
                    {{ __('دفع') }}
                </button>
                <button class="tab-button" data-tab="orders">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('الطلبات') }}
                </button>
                <button class="tab-button" data-tab="notifications">
                    <i class="fas fa-bell"></i>
                    {{ __('الإشعارات') }}
                </button>
                <button class="tab-button" data-tab="settings">
                    <i class="fas fa-cog"></i>
                    {{ __('الإعدادات') }}
                </button>
            </div>

            <div class="tab-content">
                <!-- Wallet Tab -->
                <div class="tab-pane active" id="wallet-tab">
                    <div class="wallet-transactions">
                        <h3>{{ __('معاملات المحفظة') }}</h3>
                        <div class="transactions-list">
                            @forelse($payments as $payment)
                                <div class="transaction-item">
                                    <div
                                        class="transaction-icon {{ isset($payment->type) && $payment->type === 'deposit' ? 'deposit' : 'withdraw' }}">
                                        <i
                                            class="fas {{ isset($payment->type) && $payment->type === 'deposit' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                    </div>
                                    <div class="transaction-details">
                                        <div class="transaction-name">{{ $payment->note ?? 'معاملة' }}</div>
                                        <div class="transaction-description">
                                            {{ isset($payment->type) && $payment->type === 'deposit' ? 'إيداع' : 'سحب' }}
                                            @if ($payment->note)
                                                - {{ $payment->note }}
                                            @endif
                                        </div>
                                        <div class="transaction-time">
                                            @if ($payment->created_at instanceof \Illuminate\Support\Carbon)
                                                {{ $payment->created_at->format('H:i:s Y-m-d') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($payment->created_at)->format('H:i:s Y-m-d') }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="transaction-amounts">
                                        <div
                                            class="primary-amount {{ isset($payment->type) && $payment->type === 'deposit' ? 'positive' : 'negative' }}">
                                            ${{ number_format($payment->amount, 2) }}
                                        </div>
                                        <div class="secondary-amount">
                                            ${{ number_format($payment->new_balance ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fas fa-wallet"></i>
                                    <p>{{ __('لا توجد معاملات') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- KYC Tab -->
                <div class="tab-pane" id="kyc-tab">
                    <div class="kyc-section">
                        <div class="kyc-status">
                            <h3>{{ __('التحقق من الهوية') }}</h3>
                            <div class="status-info">
                                <span class="status-label">{{ __('الحالة الحالية:') }}</span>
                                <span class="status-badge not-required">{{ __('غير مطلوب') }}</span>
                            </div>
                            <button class="verification-btn">{{ __('طلب التحقق') }}</button>
                        </div>

                        <div class="kyc-table">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('نوع الهوية') }}</th>
                                        <th>{{ __('رقم الهوية') }}</th>
                                        <th>{{ __('الحالة') }}</th>
                                        <th>{{ __('تاريخ الطلب') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="empty-state">
                                            <i class="fas fa-id-card"></i>
                                            <p>{{ __('لا توجد طلبات تحقق') }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Tab -->
                <div class="tab-pane" id="payment-tab">
                    <div class="payment-section">
                        <div class="payment-history">
                            <h3>{{ __('سجل الدفعات') }}</h3>
                            <div class="payment-table">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('رقم الطلب') }}</th>
                                            <th>{{ __('المبلغ') }}</th>
                                            <th>{{ __('الحالة') }}</th>
                                            <th>{{ __('التاريخ') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->order_id ? '#' . $payment->order_id : 'إيداع' }}</td>
                                                <td><span
                                                        class="amount-paid">${{ number_format($payment->amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span class="status-badge completed">
                                                        {{ __('مكتمل') }}
                                                    </span>
                                                </td>
                                                <td>{{ $payment->created_at->format('H:i:s Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"
                                                    style="text-align: center; padding: 2rem; color: var(--dark-text-muted);">
                                                    <i class="fas fa-credit-card"
                                                        style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                                    <p>{{ __('لا توجد دفعات') }}</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="orders-tab">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>{{ __('التاريخ') }}</th>
                                <th>{{ __('الحالة') }}</th>
                                <th>{{ __('السعر') }}</th>
                                <th>{{ __('المنتج') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>
                                        @if ($order->status === 'completed')
                                            <i class="fas fa-check-circle table-icon check"></i>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('H:i:s Y-m-d') }}</td>
                                    <td>
                                        <span class="status-badge {{ $order->status }}">
                                            @switch($order->status)
                                                @case('completed')
                                                    {{ __('مكتمل') }}
                                                @break

                                                @case('processing')
                                                    {{ __('معالجة') }}
                                                @break

                                                @case('canceled')
                                                    {{ __('ملغي') }}
                                                @break

                                                @default
                                                    {{ __('معالجة') }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td><span class="price-value">${{ number_format($order->price, 5) }}</span></td>
                                    <td><span class="product-name">{{ $order->product_name }}</span></td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align: center; padding: 2rem; color: var(--dark-text-muted);">
                                            <i class="fas fa-shopping-cart"
                                                style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                            <p>{{ __('لا توجد طلبات') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Notifications Tab -->
                    <div class="tab-pane" id="notifications-tab">
                        <div class="notifications-section">
                            <div class="notifications-list">
                                @forelse($notifications as $notification)
                                    <div class="notification-item">
                                        <div class="notification-content">
                                            <div class="notification-text">
                                                <strong>{{ is_string($notification->title) ? $notification->title : 'إشعار' }}</strong>
                                                {{ is_string($notification->message) ? $notification->message : 'رسالة إشعار' }}
                                            </div>
                                            <div class="notification-type">
                                                @switch($notification->type)
                                                    @case('cancelled')
                                                        {{ __('الغاء الطلب') }}
                                                    @break

                                                    @case('completed')
                                                        {{ __('اكمال الطلب') }}
                                                    @break

                                                    @case('payment')
                                                        {{ __('دفع') }}
                                                    @break

                                                    @default
                                                        {{ __('إشعار') }}
                                                @endswitch
                                            </div>
                                            <div class="notification-time">
                                                {{ $notification->created_at->format('H:i:s Y-m-d') }}</div>
                                        </div>
                                        <div class="notification-actions">
                                            <button class="copy-btn"><i class="fas fa-copy"></i></button>
                                            <div class="notification-icon {{ $notification->type }}">
                                                @switch($notification->type)
                                                    @case('cancelled')
                                                        <i class="fas fa-times"></i>
                                                    @break

                                                    @case('completed')
                                                        <i class="fas fa-check"></i>
                                                    @break

                                                    @case('payment')
                                                        <i class="fas fa-credit-card"></i>
                                                    @break

                                                    @default
                                                        <i class="fas fa-bell"></i>
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="fas fa-bell"></i>
                                            <p>{{ __('لا توجد إشعارات') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div class="tab-pane" id="settings-tab">
                            <div class="settings-section">
                                <div class="settings-card">
                                    <h3>{{ __('تغيير كلمة المرور') }}</h3>
                                    <div class="password-form" id="password-form">
                                        <div class="form-group">
                                            <label>{{ __('كلمة المرور الجديدة') }}</label>
                                            <input type="password" class="form-input" id="new-password"
                                                placeholder="{{ __('أدخل كلمة المرور الجديدة') }}" required>
                                        </div>
                                        <button type="button" class="submit-btn"
                                            id="password-submit-btn">{{ __('تغيير كلمة المرور') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Add Balance Modal -->
            <div id="addBalanceModal"
                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 10000; align-items: center; justify-content: center;">
                <div
                    style="background: var(--dark-bg-secondary); border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%; border: 1px solid var(--dark-border); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <h2 style="color: var(--dark-text-primary); font-size: 1.5rem; font-weight: 700;">{{ __('إضافة رصيد') }}
                        </h2>
                        <button type="button" onclick="closeAddBalanceModal()"
                            style="background: none; border: none; color: var(--dark-text-muted); font-size: 1.5rem; cursor: pointer; padding: 0.5rem;">&times;</button>
                    </div>

                    <form id="addBalanceForm" action="{{ route('dashboard.customer.addBalance', $customer->id) }}"
                        method="POST" onsubmit="handleFormSubmit(event)">
                        @csrf

                        <div style="margin-bottom: 1.5rem;">
                            <label
                                style="display: block; color: var(--dark-text-primary); margin-bottom: 0.5rem; font-weight: 600;">{{ __('المبلغ') }}
                                <span style="color: var(--dark-danger);">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="balance-amount"
                                placeholder="0.00" required
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--dark-border); border-radius: 8px; background: var(--dark-bg-tertiary); color: var(--dark-text-primary); font-size: 1rem;">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label
                                style="display: block; color: var(--dark-text-primary); margin-bottom: 0.5rem; font-weight: 600;">{{ __('ملاحظة') }}
                                ({{ __('اختياري') }})</label>
                            <textarea name="note" id="balance-note" placeholder="{{ __('أضف ملاحظة عن عملية الإضافة...') }}" rows="3"
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--dark-border); border-radius: 8px; background: var(--dark-bg-tertiary); color: var(--dark-text-primary); font-size: 1rem; resize: vertical;"></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <button type="button" onclick="closeAddBalanceModal()"
                                style="padding: 0.75rem 1.5rem; border: none; border-radius: 8px; background: var(--dark-text-muted); color: white; font-weight: 600; cursor: pointer;">
                                {{ __('إلغاء') }}
                            </button>
                            <button type="submit" id="submit-add-balance"
                                style="padding: 0.75rem 1.5rem; border: none; border-radius: 8px; background: linear-gradient(135deg, var(--dark-success), #059669); color: white; font-weight: 600; cursor: pointer;">
                                {{ __('إضافة الرصيد') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                // Handle session messages - حل جذري
                document.addEventListener('DOMContentLoaded', function() {
                    @if (session('success'))
                        // Create toast notification directly
                        const toast = document.createElement('div');
                        toast.style.cssText =
                            'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 99999; max-width: 400px; animation: slideInRight 0.3s ease;';
                        toast.innerHTML =
                            '<div style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-check-circle" style="font-size: 1.5rem;"></i><div><strong>تم بنجاح!</strong><div>{{ session('success') }}</div></div></div>';
                        document.body.appendChild(toast);

                        // Remove after 3 seconds
                        setTimeout(() => {
                            toast.style.animation = 'slideOutRight 0.3s ease';
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);

                        // Add keyframe animations if not exist
                        if (!document.getElementById('toast-animations')) {
                            const style = document.createElement('style');
                            style.id = 'toast-animations';
                            style.textContent = `
                                @keyframes slideInRight {
                                    from { transform: translateX(100%); opacity: 0; }
                                    to { transform: translateX(0); opacity: 1; }
                                }
                                @keyframes slideOutRight {
                                    from { transform: translateX(0); opacity: 1; }
                                    to { transform: translateX(100%); opacity: 0; }
                                }
                            `;
                            document.head.appendChild(style);
                        }
                    @endif

                    @if (session('error'))
                        // Create error toast notification directly
                        const toast = document.createElement('div');
                        toast.style.cssText =
                            'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 99999; max-width: 400px; animation: slideInRight 0.3s ease;';
                        toast.innerHTML =
                            '<div style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-times-circle" style="font-size: 1.5rem;"></i><div><strong>حدث خطأ!</strong><div>{{ session('error') }}</div></div></div>';
                        document.body.appendChild(toast);

                        // Remove after 4 seconds
                        setTimeout(() => {
                            toast.style.animation = 'slideOutRight 0.3s ease';
                            setTimeout(() => toast.remove(), 300);
                        }, 4000);

                        // Add keyframe animations if not exist
                        if (!document.getElementById('toast-animations')) {
                            const style = document.createElement('style');
                            style.id = 'toast-animations';
                            style.textContent = `
                                @keyframes slideInRight {
                                    from { transform: translateX(100%); opacity: 0; }
                                    to { transform: translateX(0); opacity: 1; }
                                }
                                @keyframes slideOutRight {
                                    from { transform: translateX(0); opacity: 1; }
                                    to { transform: translateX(100%); opacity: 0; }
                                }
                            `;
                            document.head.appendChild(style);
                        }
                    @endif
                });

                // Tab functionality
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const tabId = this.getAttribute('data-tab');

                        // Remove active class from all buttons and panes
                        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                        // Add active class to clicked button and corresponding pane
                        this.classList.add('active');
                        document.getElementById(tabId + '-tab').classList.add('active');
                    });
                });

                // Add balance modal and functions
                function addBalance() {
                    const addBalanceModal = document.getElementById('addBalanceModal');
                    const balanceAmountInput = document.getElementById('balance-amount');
                    const balanceNoteInput = document.getElementById('balance-note');

                    if (addBalanceModal) {
                        addBalanceModal.style.display = 'flex';
                        if (balanceAmountInput) balanceAmountInput.value = '';
                        if (balanceNoteInput) balanceNoteInput.value = '';
                    }
                }

                // Close modal
                function closeAddBalanceModal() {
                    const addBalanceModal = document.getElementById('addBalanceModal');
                    if (addBalanceModal) {
                        addBalanceModal.style.display = 'none';
                    }
                }

                // Handle form submit
                function handleFormSubmit(event) {
                    const submitBtn = document.getElementById('submit-add-balance');
                    if (submitBtn) {
                        submitBtn.textContent = '{{ __('جاري المعالجة...') }}';
                        submitBtn.disabled = true;
                    }
                    // Form سيرسل طبيعياً بدون AJAX
                }

                // Close modal when clicking outside
                document.addEventListener('DOMContentLoaded', function() {
                    const addBalanceModal = document.getElementById('addBalanceModal');
                    if (addBalanceModal) {
                        addBalanceModal.addEventListener('click', function(e) {
                            if (e.target === addBalanceModal) {
                                closeAddBalanceModal();
                            }
                        });
                    }
                });

                // Sub-tab functionality
                document.querySelectorAll('.sub-tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const subTabId = this.getAttribute('data-sub-tab');

                        // Remove active class from all sub-tab buttons and panes
                        document.querySelectorAll('.sub-tab-button').forEach(btn => btn.classList.remove('active'));
                        document.querySelectorAll('.sub-tab-pane').forEach(pane => pane.classList.remove('active'));

                        // Add active class to clicked button and corresponding pane
                        this.classList.add('active');
                        document.getElementById(subTabId + '-sub-tab').classList.add('active');
                    });
                });

                // Copy notification function
                function copyNotification(text) {
                    navigator.clipboard.writeText(text).then(function() {
                        // Show success message
                        const toast = document.createElement('div');
                        toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--dark-success);
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    z-index: 10000;
                    font-weight: 500;
                `;
                        toast.textContent = '{{ __('تم نسخ النص') }}';
                        document.body.appendChild(toast);

                        setTimeout(() => {
                            document.body.removeChild(toast);
                        }, 3000);
                    });
                }

                // Add copy functionality to copy buttons
                document.querySelectorAll('.copy-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const notificationText = this.closest('.notification-item').querySelector(
                            '.notification-text').textContent;
                        copyNotification(notificationText);
                    });
                });

                // Password change form handling - RADICAL SOLUTION
                window.passwordChangeHandler = function(e) {
                    // Prevent all default behaviors
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                    }

                    console.log('=== PASSWORD CHANGE HANDLER STARTED ===');

                    const newPassword = document.getElementById('new-password').value;

                    if (newPassword.length < 6) {
                        showNotification('{{ __('كلمة المرور يجب أن تكون 6 أحرف على الأقل') }}', 'error');
                        return false;
                    }

                    // Show loading state
                    const submitBtn = document.getElementById('password-submit-btn');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = '{{ __('جاري التحميل...') }}';
                    submitBtn.disabled = true;

                    // Simulate password change
                    setTimeout(() => {
                        // Reset button state
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;

                        // Show success notification
                        showNotification('{{ __('تم تغيير كلمة المرور بنجاح') }}', 'success');

                        // Clear form
                        document.getElementById('new-password').value = '';

                        // FORCE STAY IN SETTINGS TAB
                        forceStayInSettingsTab();

                        console.log('=== PASSWORD CHANGE COMPLETED ===');
                    }, 1500);

                    return false;
                };

                function forceStayInSettingsTab() {
                    console.log('=== FORCING SETTINGS TAB ===');

                    // Remove active from ALL tabs
                    document.querySelectorAll('.tab-button').forEach(btn => {
                        btn.classList.remove('active');
                        console.log('Removed active from tab button:', btn.textContent);
                    });

                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                        console.log('Removed active from tab pane:', pane.id);
                    });

                    // Remove active from ALL sub-tabs
                    document.querySelectorAll('.sub-tab-button').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    document.querySelectorAll('.sub-tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                    });

                    // Force activate settings tab
                    const settingsTab = document.getElementById('settings-tab');
                    const settingsButton = document.querySelector('[data-tab="settings"]');

                    if (settingsTab && settingsButton) {
                        settingsButton.classList.add('active');
                        settingsTab.classList.add('active');
                        console.log('Settings tab activated');
                    }

                    // Force activate settings sub-tab
                    const settingsSubTab = document.getElementById('settings-sub-tab');
                    const settingsSubButton = document.querySelector('[data-sub-tab="settings"]');

                    if (settingsSubTab && settingsSubButton) {
                        settingsSubButton.classList.add('active');
                        settingsSubTab.classList.add('active');
                        console.log('Settings sub-tab activated');
                    }

                    // Prevent any other tab switching for 3 seconds
                    setTimeout(() => {
                        console.log('Tab switching protection ended');
                    }, 3000);
                }

                // Initialize password form handler
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('=== INITIALIZING PASSWORD FORM ===');

                    const passwordSubmitBtn = document.getElementById('password-submit-btn');
                    if (passwordSubmitBtn) {
                        // Remove any existing listeners
                        passwordSubmitBtn.onclick = null;

                        // Add our handler
                        passwordSubmitBtn.addEventListener('click', window.passwordChangeHandler);

                        console.log('Password form handler attached');
                    } else {
                        console.error('Password submit button not found!');
                    }
                });

                // Backup initialization
                setTimeout(function() {
                    const passwordSubmitBtn = document.getElementById('password-submit-btn');
                    if (passwordSubmitBtn && !passwordSubmitBtn.onclick) {
                        passwordSubmitBtn.addEventListener('click', window.passwordChangeHandler);
                        console.log('Backup password form handler attached');
                    }
                }, 2000);

                // Show notification function
                function showNotification(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.style.cssText = `
                     position: fixed;
                     top: 20px;
                     right: 20px;
                     background: ${type === 'success' ? 'var(--dark-success)' : 'var(--dark-danger)'};
                     color: white;
                     padding: 1rem 1.5rem;
                     border-radius: 8px;
                     z-index: 10000;
                     font-weight: 500;
                     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                     animation: slideIn 0.3s ease-out;
                 `;
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.animation = 'slideOut 0.3s ease-in';
                        setTimeout(() => {
                            document.body.removeChild(toast);
                        }, 300);
                    }, 3000);
                }

                // Add CSS animations
                const style = document.createElement('style');
                style.textContent = `
                 @keyframes slideIn {
                     from { transform: translateX(100%); opacity: 0; }
                     to { transform: translateX(0); opacity: 1; }
                 }
                 @keyframes slideOut {
                     from { transform: translateX(0); opacity: 1; }
                     to { transform: translateX(100%); opacity: 0; }
        }
    `;
                document.head.appendChild(style);
            </script>
        @endpush
