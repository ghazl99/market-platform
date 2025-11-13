@extends('digital.themes.app')
@section('title', __('Wallet'))
@push('styles')
    <style>
        .quick-date-btn.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Wallet Page Specific Styles */
        .wallet-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .wallet-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(5, 150, 105, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .wallet-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Page Header */
        .wallet-header {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .wallet-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .wallet-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            margin: 0;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Balance Card */
        .balance-card {
            background: var(--gradient-primary);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(5, 150, 105, 0.3);
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }

        .balance-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .balance-info h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .balance-amount {
            font-size: 3rem;
            font-weight: 900;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .balance-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .balance-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .balance-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Stats Container */
        .stats-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .stats-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 24px 24px 0 0;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .stats-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stats-title i {
            color: var(--gradient-primary);
            font-size: 1.5rem;
        }

        .scroll-indicators {
            display: flex;
            gap: 0.5rem;
        }

        .scroll-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            background: #ffffff;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .scroll-btn:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--gradient-primary);
            transform: scale(1.1);
        }

        .scroll-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .stats-scroll-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            margin-bottom: 1rem;
        }

        .stats-scroll-wrapper::-webkit-scrollbar {
            display: none;
        }

        .stats-grid {
            display: flex;
            gap: 1.5rem;
            padding: 0.5rem 0;
            min-width: max-content;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 220px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 20px 20px 0 0;
        }

        .stat-card.primary::before {
            background: var(--gradient-primary);
        }

        .stat-card.success::before {
            background: var(--gradient-primary);
        }

        .stat-card.error::before {
            background: var(--gradient-primary);
        }

        .stat-card.purple::before {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
        }

        .stat-card.info::before {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .stat-card.warning::before {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-card.secondary::before {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-bottom: 0.75rem;
        }

        .stat-icon.income {
            background: var(--gradient-primary);
        }

        .stat-icon.expense {
            background: var(--gradient-primary);
        }

        .stat-icon.pending {
            background: var(--gradient-primary);
        }

        .stat-content {
            margin-bottom: 0.75rem;
        }

        .stat-amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111827;
            margin: 0;
            line-height: 1;
        }

        .stat-currency {
            font-size: 1.1rem;
            font-weight: 600;
            color: #6b7280;
            margin-right: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin: 0 0 0.75rem 0;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.2rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 16px;
            background: #f0fdf4;
            color: var(--gradient-primary);
        }

        .stat-trend.negative {
            background: #fef2f2;
            color: var(--gradient-primary);
        }

        .stat-trend.neutral {
            background: #f3f4f6;
            color: #6b7280;
        }

        .stat-trend i {
            font-size: 0.8rem;
        }

        .scroll-progress {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 2px;
            transition: width 0.3s ease;
            width: 0%;
        }


        /* Date Filter Section */
        .date-filter-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .date-filter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 24px 24px 0 0;
        }

        .filter-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .filter-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .filter-title i {
            color: var(--gradient-primary);
            font-size: 1.5rem;
        }

        .filter-subtitle {
            font-size: 1rem;
            color: #6b7280;
            margin: 0;
        }

        .date-filters {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto;
            gap: 2rem;
            align-items: end;
            margin-bottom: 2rem;
        }

        .date-input-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .date-label {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .date-label i {
            color: var(--gradient-primary);
            font-size: 0.9rem;
        }

        .date-input-wrapper {
            position: relative;
        }

        .date-input {
            width: 100%;
            padding: 1rem 1.25rem;
            padding-left: 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #111827;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .date-input:focus {
            outline: none;
            border-color: var(--gradient-primary);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
            transform: translateY(-1px);
        }

        .date-input:hover {
            border-color: var(--gradient-primary);
        }

        .date-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            pointer-events: none;
        }

        .date-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        .search-btn,
        .clear-btn {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .search-btn.primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .search-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
        }

        .clear-btn.secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }

        .clear-btn.secondary:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            transform: translateY(-2px);
        }

        .quick-date-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .quick-date-btn {
            padding: 0.75rem 1.25rem;
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .quick-date-btn:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--gradient-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .quick-date-btn i {
            font-size: 0.8rem;
        }

        /* Responsive Design for Date Filter */
        @media (max-width: 768px) {
            .date-filter-section {
                padding: 1.5rem;
            }

            .filter-title {
                font-size: 1.5rem;
            }

            .date-filters {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .date-separator {
                display: none;
            }

            .filter-actions {
                justify-content: center;
            }

            .quick-date-buttons {
                gap: 0.5rem;
            }

            .quick-date-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .date-filter-section {
                padding: 1rem;
            }

            .filter-title {
                font-size: 1.25rem;
            }

            .date-input {
                padding: 0.75rem 1rem;
                padding-left: 2.5rem;
            }

            .search-btn,
            .clear-btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .quick-date-buttons {
                flex-direction: column;
                align-items: center;
            }

            .quick-date-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Responsive Design for Stats */
        @media (max-width: 768px) {
            .stats-container {
                padding: 1.5rem;
            }

            .stats-title {
                font-size: 1.5rem;
            }

            .stat-card {
                min-width: 200px;
                padding: 1.25rem;
            }

            .stat-amount {
                font-size: 1.6rem;
            }

            .scroll-btn {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .stats-container {
                padding: 1rem;
            }

            .stats-header {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }

            .stats-title {
                font-size: 1.25rem;
            }

            .stat-card {
                min-width: 180px;
                padding: 1rem;
            }

            .stat-amount {
                font-size: 1.4rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .scroll-indicators {
                gap: 0.25rem;
            }

            .scroll-btn {
                width: 30px;
                height: 30px;
                font-size: 0.7rem;
            }
        }

        /* Transactions Section */
        .transactions-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .transactions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .transactions-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #111827;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .filter-tab.active {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--gradient-primary);
        }

        .filter-tab:hover {
            background: var(--gradient-primary);
            color: white;
        }

        .transactions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .transaction-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .transaction-item:hover::before {
            transform: scaleY(1);
        }

        .transaction-item:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .transaction-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .transaction-icon.income {
            background: var(--gradient-primary);
        }

        .transaction-icon.expense {
            background: var(--gradient-secondary);
        }

        .transaction-icon.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .transaction-details {
            flex: 1;
            min-width: 0;
        }

        .transaction-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin: 0 0 0.25rem 0;
        }

        .transaction-desc {
            font-size: 0.85rem;
            color: #374151;
            margin: 0;
        }

        .transaction-amount {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .transaction-amount.positive {
            color: #10b981;
        }

        .transaction-amount.negative {
            color: #ef4444;
        }

        .transaction-amount.pending {
            color: #f59e0b;
        }

        .transaction-date {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        /* New Transaction Design */
        .transaction-avatar {
            margin-left: 1rem;
        }

        .avatar-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .avatar-btn.error {
            background: var(--error-light);
            color: var(--error);
        }

        .avatar-btn.success {
            background: var(--success-light);
            color: var(--success);
        }

        .avatar-btn:hover {
            transform: scale(1.1);
        }

        .transaction-content {
            flex: 1;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .transaction-action {
            text-align: left;
        }

        .transaction-balance {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .old-balance {
            color: var(--error);
            text-decoration: line-through;
        }

        .new-balance {
            color: var(--success);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .wallet-container {
                padding: 0 1rem;
            }

            .balance-content {
                flex-direction: column;
                text-align: center;
            }

            .balance-amount {
                font-size: 2.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .transactions-header {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-tabs {
                justify-content: center;
            }

            .transaction-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .transaction-icon {
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }

            .balance-actions {
                flex-direction: column;
            }

            .balance-btn {
                width: 100%;
            }
        }

        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg,
                    var(--bg-secondary) 25%,
                    rgba(255, 255, 255, 0.1) 50%,
                    var(--bg-secondary) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }
    </style>
@endpush
@section('content')
    <section class="wallet-section">
        <div class="wallet-container">
            <!-- Page Header -->
            <div class="wallet-header">
                <h2 class="wallet-title">{{ __('Wallet') }}</h2>
            </div>

            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-content">
                    <div class="balance-info">
                        <h2>{{ __('Current Balance') }}</h2>
                        <p class="balance-amount">
                            {{ number_format(Auth::user()->walletForStore()->first()?->balance ?? 0, 2) }} $</p>
                    </div>
                    <div class="balance-actions">
                        <a class="balance-btn" href="{{ Route('payment-method.index') }}" style="text-decoration: none">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Balance') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Date Filter Section -->
            <div class="date-filter-section">
                <div class="filter-header">
                    <h3 class="filter-title">
                        <i class="fas fa-calendar-alt"></i>
                        {{ __('Filter by Date') }}
                    </h3>
                    <div class="filter-subtitle">{{ __('Choose date range to view transactions') }}</div>
                </div>

                <form id="filterForm" method="GET">
                    <div class="date-filters">
                        <div class="date-input-group">
                            <label class="date-label">
                                <i class="fas fa-calendar-plus"></i> {{ __('From Date') }}
                            </label>
                            <div class="date-input-wrapper">
                                <input type="date" class="date-input" id="fromDate" name="date_from">
                                <i class="fas fa-calendar-check date-icon"></i>
                            </div>
                        </div>

                        <div class="date-separator">
                            <i class="fas fa-arrow-left"></i>
                        </div>

                        <div class="date-input-group">
                            <label class="date-label">
                                <i class="fas fa-calendar-minus"></i> {{ __('To Date') }}
                            </label>
                            <div class="date-input-wrapper">
                                <input type="date" class="date-input" id="toDate" name="date_to">
                                <i class="fas fa-calendar-check date-icon"></i>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="search-btn primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="clear-btn secondary" onclick="clearDateFilter()">
                                <i class="fas fa-times"></i> {{ __('Clear') }}
                            </button>
                        </div>
                    </div>

                    <div class="quick-date-buttons">
                        <button type="button" class="quick-date-btn" onclick="setQuickDate('today')">
                            <i class="fas fa-calendar-day"></i> {{ __('Today') }}
                        </button>
                        <button type="button" class="quick-date-btn" onclick="setQuickDate('week')">
                            <i class="fas fa-calendar-week"></i> {{ __('This Week') }}
                        </button>
                        <button type="button" class="quick-date-btn" onclick="setQuickDate('month')">
                            <i class="fas fa-calendar"></i> {{ __('This Month') }}
                        </button>
                        <button type="button" class="quick-date-btn" onclick="setQuickDate('year')">
                            <i class="fas fa-calendar-alt"></i> {{ __('This Year') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="transactions-list" id="transactionsList">
                @forelse($transactions as $transaction)
                    <div class="transaction-item" data-type="{{ $transaction->type }}">
                        <div class="transaction-avatar">
                            <button class="avatar-btn {{ $transaction->type == 'refunds' ? 'success' : 'error' }}">
                                <i class="fas {{ $transaction->type == 'refunds' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                            </button>
                        </div>
                        <div class="transaction-content">
                            <h5 class="transaction-title">
                                @if ($transaction->order)
                                    {{ $transaction->order->items->first()?->product->name ?? __('Order Item') }}
                                @elseif($transaction->type == 'deposit' && $transaction->paymentRequest)
                                    {{ __('Add Balance') }}
                                    ({{ number_format($transaction->amount, 2) }} $)
                                    — <small>{{ __('Payment Method:') }}
                                        {{ $transaction->paymentRequest?->paymentMethod?->name ?? '-' }}</small>
                                @endif
                            </h5>

                            @php
                                $date = $transaction->created_at_in_store_timezone;
                                $formatted = $date?->format('Y-m-d h:i A');
                                if ($formatted && app()->getLocale() == 'ar') {
                                    $formatted = str_replace(['AM', 'PM'], ['صباحًا', 'مساءً'], $formatted);
                                }
                            @endphp

                            @if ($formatted)
                                <small class="transaction-date">{{ $formatted }}</small>
                            @endif
                        </div>

                        <div class="transaction-action">
                            <h4 class="transaction-amount {{ $transaction->type == 'deposit' ? 'positive' : '' }}">
                                {{ $transaction->type == 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                                $
                            </h4>
                            <small class="transaction-balance">
                                <s class="old-balance">{{ number_format($transaction->old_balance, 2) }}</s>
                                - <span class="new-balance">{{ number_format($transaction->new_balance, 2) }}</span>
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-wallet"></i>
                        <h3>{{ __('No Transactions Found') }}</h3>
                        <p>{{ __('No transactions found for the selected period.') }}</p>
                    </div>
                @endforelse


                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        function clearDateFilter() {
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            document.querySelector('input[name="quick"]')?.remove();
            document.getElementById('filterForm').submit();
        }

        function setQuickDate(period) {
            // إزالة التفعيل من كل الأزرار
            document.querySelectorAll('.quick-date-btn').forEach(btn => btn.classList.remove('active'));

            // تحديد الزر الذي تم الضغط عليه
            const activeBtn = event.currentTarget;
            activeBtn.classList.add('active');

            // حذف أي فلاتر سابقة
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';

            // إضافة input مخفي للفترة السريعة
            let existingQuick = document.querySelector('input[name="quick"]');
            if (existingQuick) existingQuick.remove();

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'quick';
            input.value = period;
            document.getElementById('filterForm').appendChild(input);

            document.getElementById('filterForm').submit();
        }

        // عند تحميل الصفحة، ميّز الزر حسب الفلتر الحالي
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const quick = urlParams.get('quick');
            if (quick) {
                document.querySelector(`.quick-date-btn[onclick*="${quick}"]`)?.classList.add('active');
            }
        });
    </script>
@endpush
