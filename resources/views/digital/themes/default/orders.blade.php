@extends('digital.themes.app')
@section('title', __('My Orders'))
@push('styles')
    <style>
        /* Orders Page Specific Styles */

        .orders-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
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
                radial-gradient(circle at 20% 80%, rgba(5, 150, 105, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .orders-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Page Header */
        .orders-header {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .orders-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .orders-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Filters Section */
        .filters-section {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
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
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .date-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
        }

        .date-label {
            position: absolute;
            top: -8px;
            right: 12px;
            background: var(--bg-primary);
            padding: 0 8px;
            font-size: 0.875rem;
            color: var(--text-secondary);
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
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
        }

        .search-btn {
            padding: 0.75rem;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 50px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
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
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
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
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
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
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
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
            color: var(--text-primary);
        }

        .total-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .export-btn {
            padding: 0.75rem 2rem;
            background: var(--gradient-primary);
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
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
        }

        /* Orders List */
        .orders-list {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 2px solid var(--border-color);
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
            background: var(--gradient-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .order-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
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
            color: var(--text-primary);
            margin: 0;
        }

        .order-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .order-category {
            font-size: 0.875rem;
            color: var(--text-secondary);
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
            color: var(--text-secondary);
        }

        .order-player-id {
            font-size: 0.75rem;
            color: var(--text-secondary);
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
            color: var(--primary-color);
            margin: 0;
        }

        .price-quantity {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .orders-container {
                padding: 0 1rem;
            }

            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }

            .date-filter-group {
                flex-direction: column;
                min-width: auto;
            }

            .search-group {
                min-width: auto;
            }

            .summary-section {
                flex-direction: column;
                text-align: center;
            }

            .order-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
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
    <div class="orders-section">
        <div class="orders-container">
            <!-- Page Header -->
            <div class="orders-header">
                <h2 class="orders-title">{{ __('My Orders') }}</h2>
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
                                'manual processing' => __('Manual Processing'),
                                'confirmed' => __('Confirmed'),
                                'automatic processing' => __('Automatic Processing'),
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
                                    'manual processing' => 'pending',
                                    'confirmed', 'automatic processing' => 'accepted',
                                    'canceled' => 'rejected',
                                    default => 'manual processing',
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
                            <small class="price-quantity">{{ $order->items->first()->product->price }} $/
                                {{ $order->items->sum('quantity') }}</small>
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
