@extends('themes.app')
@section('title', __('My Orders'))
@push('styles')
    <style>
        /* Orders Page Specific Styles */
        .orders-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .orders-section::before {
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

        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Page Header */
        .orders-header {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .orders-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            pointer-events: none;
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
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Orders Stats */
        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.processing {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, #4ade80, #22c55e);
        }

        .stat-icon.cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
        }

        /* Filters Section */
        .filters-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .filters-row {
            display: flex;
            gap: 1rem;
            align-items: end;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .date-filter-group {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-width: 300px;
        }

        .date-input-wrapper {
            position: relative;
            flex: 1;
        }

        .date-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            color: #333;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .date-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .date-label {
            position: absolute;
            top: -8px;
            right: 12px;
            background: #ffffff;
            padding: 0 8px;
            font-size: 0.875rem;
            color: #666;
            font-weight: 500;
        }

        .search-group {
            display: flex;
            gap: 0.5rem;
            align-items: end;
            min-width: 300px;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            color: #333;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .search-btn {
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 50px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            background: #f8f9fa;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Status Chips */
        .status-chips {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 25px;
            background: #f8f9fa;
            border: 2px solid #e5e7eb;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .status-chip::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .status-chip:hover::before {
            left: 100%;
        }

        .status-chip.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .status-chip .count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-chip.active .count {
            background: rgba(255, 255, 255, 0.3);
        }

        .status-icon {
            font-size: 0.875rem;
        }

        /* Summary Section */
        .summary-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .total-amount {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .total-label {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .total-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #667eea;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .export-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Orders List */
        .orders-list {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .orders-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }

        .view-toggle {
            display: flex;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 0.25rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: #667eea;
            color: white;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-radius: 16px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .order-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .order-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .order-item:hover::before {
            transform: scaleY(1);
        }

        .order-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .order-id {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .order-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .order-category {
            font-size: 0.875rem;
            color: #666;
            margin: 0;
        }

        .order-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
        }

        .order-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .order-status.accepted {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .order-status.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .order-status.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .order-time {
            font-size: 0.75rem;
            color: #666;
        }

        .order-player-id {
            font-size: 0.75rem;
            color: #666;
            font-family: monospace;
        }

        .order-price {
            display: flex;
            flex-direction: column;
            align-items: end;
            min-width: 100px;
        }

        .price-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #667eea;
            margin: 0;
        }

        .price-quantity {
            font-size: 0.75rem;
            color: #666;
        }

        .order-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: transparent;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .btn-danger:hover {
            background: #ef4444;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-icon {
            font-size: 4rem;
            color: #e5e7eb;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-description {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .btn-explore {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pagination-btn {
            background: #ffffff;
            color: #667eea;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .orders-container {
                padding: 0 1.5rem;
            }

            .filters-row {
                flex-direction: column;
                gap: 1rem;
            }

            .date-filter-group {
                width: 100%;
            }

            .search-group {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .orders-container {
                padding: 0 1rem;
            }

            .orders-header {
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

            .orders-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 0.8rem;
            }

            .filters-section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .filters-row {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .date-filter-group {
                flex-direction: column;
                min-width: auto;
                width: 100%;
            }

            .search-group {
                min-width: auto;
                width: 100%;
            }

            .status-chips {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .status-chip {
                font-size: 0.8rem;
                padding: 0.5rem 0.75rem;
            }

            .summary-section {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .total-value {
                font-size: 2rem;
            }

            .orders-header-section {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .section-title {
                font-size: 1.25rem;
                text-align: center;
            }

            .view-toggle {
                justify-content: center;
            }

            .order-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                padding: 1rem;
            }

            .order-info {
                order: 1;
            }

            .order-details {
                order: 2;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                min-width: auto;
            }

            .order-price {
                order: 3;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                min-width: auto;
            }

            .price-value {
                font-size: 1.1rem;
            }

            .order-actions {
                order: 4;
                justify-content: center;
                margin-top: 0.5rem;
            }

            .btn-action {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .pagination {
                margin-top: 1.5rem;
                gap: 0.5rem;
            }

            .pagination-btn {
                padding: 0.6rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .orders-container {
                padding: 0 0.75rem;
            }

            .orders-header {
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

            .orders-stats {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .filters-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .status-chips {
                gap: 0.25rem;
            }

            .status-chip {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }

            .summary-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .total-value {
                font-size: 1.75rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .order-item {
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .order-id {
                font-size: 1rem;
            }

            .order-title {
                font-size: 0.9rem;
            }

            .order-category {
                font-size: 0.8rem;
            }

            .order-details {
                gap: 0.5rem;
            }

            .order-status {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }

            .order-time {
                font-size: 0.7rem;
            }

            .order-player-id {
                font-size: 0.7rem;
            }

            .price-value {
                font-size: 1rem;
            }

            .price-quantity {
                font-size: 0.7rem;
            }

            .btn-action {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }

            .pagination {
                margin-top: 1rem;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .pagination-btn {
                padding: 0.5rem 0.6rem;
                font-size: 0.8rem;
                min-width: 40px;
            }
        }

        .order-details {
            flex-direction: row;
            justify-content: space-between;
            min-width: auto;
        }

        .order-price {
            align-items: center;
        }
        }
    </style>
@endpush
@section('content')
    <!-- Orders Section -->
    <div class="orders-section">
        <div class="orders-container">
            <!-- Page Header -->
            <div class="orders-header">
                <h2 class="page-title">{{ __('My Orders') }}</h2>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <form method="GET" action="{{ route('order.index') }}" class="filters-row">
                    <div class="date-filter-group">
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" name="date_from" value="{{ request('date_from') }}">
                            <label class="date-label">{{ __('From') }}</label>
                        </div>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" name="date_to" value="{{ request('date_to') }}">
                            <label class="date-label">{{ __('To') }}</label>
                        </div>
                    </div>
                    <div class="search-group">
                        <input type="text" class="search-input" name="search"
                            placeholder="{{ __('Search product or status...') }}" value="{{ request('search') }}">
                        <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    </div>

                    <!-- Status Chips as Buttons -->
                    <div class="status-chips">
                        @php
                            $statusLabels = [
                                'pending' => __('Pending'),
                                'confirmed' => __('Confirmed'),
                                'completed' => __('Completed'),
                                'canceled' => __('Canceled'),
                            ];
                            $currentStatus = request('status', 'all');
                        @endphp

                        <!-- All Orders Button -->
                        <button type="submit" name="status" value="all"
                            class="status-chip {{ $currentStatus == 'all' ? 'active' : '' }}">
                            <span>{{ __('All Orders') }}</span>
                            <span class="count">{{ $statusCounts['all'] }}</span>
                        </button>

                        <!-- Individual Status Buttons -->
                        @foreach ($statusLabels as $key => $label)
                            <button type="submit" name="status" value="{{ $key }}"
                                class="status-chip {{ $currentStatus == $key ? 'active' : '' }}">
                                <span>{{ $label }}</span>
                                <span class="count">{{ $statusCounts[$key] ?? 0 }}</span>
                            </button>
                        @endforeach
                    </div>
                </form>

            </div>
            <div class="summary-section">
                <div class="total-amount">
                    <span class="total-label">{{ __('Total') }}</span>
                    <span class="total-value">
                        {{ $orders->sum(fn($order) => $order->items->sum(fn($item) => $item->quantity * $item->product->price)) }}
                        $
                    </span>
                </div>
                <a href="{{ route('order.export') }}" class="export-btn"style="text-decoration: none;">
                    <i class="fas fa-file-excel"></i>
                    {{ __('Export Excel') }}
                </a>

            </div>
            <!-- Orders List -->
            <div class="orders-list">
                @forelse($orders as $order)
                    <a href="{{ route('order.show', $order->id) }}" style="text-decoration: none" class="order-item"
                        data-order-id="{{ $order->id }}">
                        <div class="order-info">
                            <h6 class="order-id">#{{ $order->id }}</h6>
                            <div class="order-title">{{ $order->items->first()->product->name ?? '' }}</div>
                            <div class="order-category">{{ $order->items->first()->product->category->name ?? '' }}</div>
                        </div>
                        <div class="order-details">
                            @php
                                $statusClass = match ($order->status) {
                                    'pending' => 'pending',
                                    'confirmed', 'completed' => 'accepted',
                                    'canceled' => 'rejected',
                                    default => 'pending',
                                };
                            @endphp
                            <div class="order-status {{ $statusClass }}">
                                @if ($statusClass == 'accepted')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($statusClass == 'rejected')
                                    <i class="fas fa-times-circle"></i>
                                @elseif($statusClass == 'pending')
                                    <i class="fas fa-clock"></i>
                                @endif
                                <span>{{ $statusLabels[$order->status] ?? $order->status }}</span>
                            </div>
                            <div class="order-time">{{ $order->created_at->format('Y-m-d H:i:s') }}</div>

                            <div class="order-player-id">
                                {{ $order->user?->player_id ? '#' . $order->user->player_id : '' }}
                            </div>
                        </div>
                        <div class="order-price">
                            <h3 class="price-value">
                                {{ $order->items->sum(fn($i) => $i->quantity * $i->product->price) }} $
                            </h3>
                            <small class="price-quantity">{{$order->items->first()->product->price  }} $/ {{ $order->items->sum('quantity') }}</small>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>{{ __('No orders') }}</h3>
                        <p>{{ __('You have not made any orders yet.') }}</p>
                    </div>
                @endforelse
            </div>
            <br>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                @if ($orders->hasPages())
                    {{ $orders->links() }}
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
