@extends('digital.themes.app')
@section('title', __('My Orders'))
@push('styles')


    <!-- إعدادات الثيم -->
    <style>
        /* Light Mode */

        /* Orders Page Styles */
        .orders-section {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }

        .orders-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .orders-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .orders-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .orders-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
        }

        /* Orders Stats */
        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px var(--shadow-color);
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
            background: var(--gradient-primary);
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
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        .stat-icon.processing {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, var(--success-color), #22c55e);
        }

        .stat-icon.cancelled {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* Filters Section */
        .filters-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
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
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .date-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 133, 51, 0.15);
        }

        .date-label {
            position: absolute;
            top: -8px;
            right: 12px;
            background: var(--card-bg);
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

        .search-input-filters {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input-filters:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 133, 51, 0.15);
        }

        .search-btn-filters {
            padding: 0.75rem;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 50px;
        }

        .search-btn-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 51, 0.3);
        }

        .filter-btn-filters {
            padding: 0.75rem 1.5rem;
            background: var(--input-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn-filters:hover {
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
            background: var(--input-bg);
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
            box-shadow: 0 8px 20px rgba(255, 133, 51, 0.3);
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
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
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

        .total-label-section {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .total-value-section {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
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
            box-shadow: 0 8px 20px rgba(255, 133, 51, 0.3);
        }

        /* Orders List Header */
        .orders-list {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
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
            color: var(--text-primary);
        }

        .view-toggle {
            display: flex;
            background: var(--input-bg);
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
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: var(--primary-color);
            color: white;
        }

        .status-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .status-filter {
            padding: 0.75rem 1.5rem;
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .status-filter:hover,
        .status-filter.active {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .order-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px var(--shadow-color);
        }

        .order-header-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-id-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .order-id-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .order-id-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .order-date-section {
            text-align: left;
        }

        .order-date {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .order-time {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .order-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .order-status-badge.pending {
            background: rgba(255, 212, 45, 0.1);
            color: var(--warning-color);
        }

        .order-status-badge.processing {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .order-status-badge.completed {
            background: rgba(60, 229, 81, 0.1);
            color: var(--success-color);
        }

        .order-status-badge.cancelled {
            background: rgba(239, 117, 117, 0.1);
            color: var(--danger-color);
        }

        .order-items {
            margin-bottom: 1.5rem;
        }

        .item-card {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: var(--input-bg);
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .item-details {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .item-price {
            text-align: left;
        }

        .price-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .total-section {
            text-align: right;
        }

        .total-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .total-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .order-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .order-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .order-btn.primary {
            background: var(--gradient-primary);
            color: white;
        }

        .order-btn.secondary {
            background: var(--card-bg);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--shadow-color);
        }

        @media (max-width: 768px) {
            .order-header-info {
                flex-direction: column;
            }

            .order-status-badge {
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
            }

            .item-card {
                padding: 0.75rem;
            }

            .item-image {
                width: 60px;
                height: 60px;
            }

            .order-summary {
                flex-direction: column;
                align-items: stretch;
            }

            .total-section {
                text-align: center;
            }

            .order-actions {
                width: 100%;
            }

            .order-btn {
                flex: 1;
            }
        }

        @media (max-width: 480px) {
            .orders-container {
                padding: 0 1rem;
            }

            .order-card {
                padding: 1.5rem;
            }

            .status-filters {
                flex-direction: column;
            }

            .status-filter {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content-adjust">
        <div class="orders-section">
            <div class="orders-container">
                <!-- Page Header -->
                <div class="orders-header">
                    <h2 class="orders-title">{{ __('My Orders') }}</h2>
                </div>
                <!-- Orders Stats -->
                <div class="orders-stats">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-number">{{ $statusCounts['manual processing'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('manual processing') }} </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon processing">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="stat-number">{{ $statusCounts['confirmed'] ?? 0 }}</div>
                        <div class="stat-label"> {{ __('Confirmed') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon completed">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="stat-number">{{ $statusCounts['automatic processing'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('automatic processing') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon cancelled">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="stat-number">{{ $statusCounts['canceled'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('Canceled') }}</div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <form method="GET" action="{{ route('order.index') }}" class="filters-row">
                        <div class="date-filter-group">
                            <div class="date-input-wrapper">
                                <input type="date" class="date-input" name="date_from"
                                    value="{{ request('date_from') }}">
                                <label class="date-label">{{ __('From') }}</label>
                            </div>
                            <div class="date-input-wrapper">
                                <input type="date" class="date-input" name="date_to" value="{{ request('date_to') }}">
                                <label class="date-label">{{ __('To') }}</label>
                            </div>
                        </div>
                        <div class="search-group">
                            <input type="text" class="search-input-filters" name="search"
                                value="{{ request('search') }}">
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
                    <div class="orders-header-section">
                        <h2 class="section-title">{{ __('All Orders') }}</h2>
                        <div class="view-toggle">
                            <button class="toggle-btn active" onclick="switchView('list')">
                                <i class="fas fa-list"></i>
                            </button>
                            <button class="toggle-btn" onclick="switchView('grid')">
                                <i class="fas fa-th"></i>
                            </button>
                        </div>
                    </div>
                    @forelse($orders as $order)
                        <div class="order-card" data-status="completed">
                            <div class="order-header-info">
                                <div class="order-id-section">
                                    <span class="order-id-label"> {{ __('Order Number') }}</span>
                                    <span class="order-id-value">#{{ $order->id }}</span>
                                </div>
                                <div class="order-date-section">
                                    <div class="order-date">{{ $order->created_at->translatedFormat('d F Y') }}</div>
                                    <div class="order-time">{{ $order->created_at->format('h:i A') }}</div>

                                </div>

                                @if ($order->status == 'automatic processing')
                                    <span class="order-status-badge completed">
                                        <i class="fas fa-check-circle"></i>
                                        {{ __('Automatic Processing') }}
                                    </span>
                                @elseif($order->status == 'canceled')
                                    <span class="order-status-badge cancelled">
                                        <i class="fas fa-times-circle"></i>
                                        {{ __('Canceled') }}
                                    </span>
                                @elseif($order->status == 'manual processing')
                                    <span class="order-status-badge pending">
                                        <i class="fas fa-clock"></i>
                                        {{ __('Manual Processing') }}
                                    </span>
                                @elseif($order->status == 'confirmed')
                                    <span class="order-status-badge processing">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        {{ __('Confirmed') }}
                                    </span>
                                @endif
                            </div>

                            <div class="order-items">
                                <div class="item-card">
                                    @php $media =  $order->items->first()->product->getFirstMedia('product_images'); @endphp

                                    @if ($media)
                                        <img src="{{ route('product.image', $media->id) }}" alt="{{  $order->items->first()->product->name ?? '' }}"
                                            class="item-image">
                                    @endif
                                    <div class="item-info">
                                        <h4 class="item-name"> {{ $order->items->first()->product->name ?? '' }}</h4>
                                        <p class="item-details">{{ __('Quantity') }} :
                                            {{ $order->items->sum('quantity') }}
                                        </p>
                                    </div>
                                    <div class="item-price">
                                        <div class="price-value"> {{ $order->items->first()->product->price }} $</div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-summary">
                                <div class="total-section">
                                    <div class="total-label">{{ __('Total') }}</div>
                                    <div class="total-value">
                                        {{ $order->items->sum(fn($i) => $i->quantity * $i->product->price) }} $</div>
                                </div>
                                <div class="order-actions">
                                    <a href="{{ route('order.show', $order->id) }}" class="order-btn secondary"
                                        style="text-decoration: none;">
                                        <i class="fas fa-eye"></i> {{ __('Details') }}
                                    </a>

                                </div>
                            </div>
                        </div>

                    @empty

                        <p>{{ __('You have not made any orders yet.') }}</p>
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
    </main>

@endsection
