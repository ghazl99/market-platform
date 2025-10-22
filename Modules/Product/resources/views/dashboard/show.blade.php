@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Product Details'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Product Show Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .product-show-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Product Header Section */
        .product-header {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #404040;
        }

        .product-title-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-title-info {
            flex: 1;
        }

        .product-status-info {
            display: flex;
            flex-direction: row;
            gap: 0.75rem;
            align-items: center;
        }

        .status-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-info.available {
            background: #10b981;
            color: #ffffff;
        }

        .status-info.api {
            background: #f59e0b;
            color: #000000;
        }

        .status-info i {
            font-size: 0.8rem;
        }

        .product-image-small {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #404040;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .product-subtitle {
            color: #a0a0a0;
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }

        .product-warning {
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 8px;
            padding: 1rem;
            color: #ffcc00;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Key Metrics Cards */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: #2d2d2d;
            border: 1px solid #404040;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #a0a0a0;
            margin-bottom: 0.5rem;
        }

        .metric-icon {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .metric-icon.up {
            color: #10b981;
        }

        .metric-icon.down {
            color: #ef4444;
        }

        .metric-icon.neutral {
            color: #6b7280;
        }

        /* Overview Section */
        .overview-section {
            margin: 2rem 0;
        }

        .overview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .overview-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .date-range-picker {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #374151;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #4b5563;
        }

        .date-input-group i {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .date-input {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 0.9rem;
            outline: none;
            cursor: pointer;
        }

        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .date-input::-webkit-datetime-edit-text {
            color: #ffffff;
        }

        .date-input::-webkit-datetime-edit-month-field,
        .date-input::-webkit-datetime-edit-day-field,
        .date-input::-webkit-datetime-edit-year-field {
            color: #ffffff;
        }

        .date-input-group:hover {
            border-color: #f59e0b;
            background: #4b5563;
            transition: all 0.3s ease;
        }

        .date-separator {
            color: #9ca3af;
            font-weight: 500;
        }

        /* Financial Summary Cards */
        .financial-summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .financial-card {
            background: #1f2937;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }

        .financial-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .financial-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .financial-card.net-profit .financial-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .financial-card.capital .financial-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .financial-card.sales-amount .financial-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .financial-card.sales-quantity .financial-icon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .financial-content {
            flex: 1;
        }

        .financial-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .financial-label {
            font-size: 0.9rem;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Navigation Tabs */
        .product-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #404040;
        }

        .tab-item {
            padding: 1rem 2rem;
            background: transparent;
            border: none;
            color: #a0a0a0;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-item.active {
            color: #ffffff;
            border-bottom-color: #f59e0b;
        }

        .tab-item:hover {
            color: #ffffff;
        }

        /* Date Range Picker */
        .date-range-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #2d2d2d;
            border-radius: 8px;
            border: 1px solid #404040;
        }

        .date-input {
            background: #3a3a3a;
            border: 1px solid #555;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            color: #ffffff;
            font-size: 0.9rem;
        }

        .date-input:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .date-label {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .display-btn {
            background: #f59e0b;
            color: #000000;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .display-btn:hover {
            background: #d97706;
        }

        /* Sales Table - Dark Theme */
        .sales-table-container {
            background: #1f2937;
            border-radius: 12px;
            border: 1px solid #374151;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            color: #ffffff;
        }

        .table th {
            background: #111827;
            color: #ffffff;
            padding: 1.25rem 1rem;
            text-align: right;
            font-weight: 600;
            border-bottom: 2px solid #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #374151;
            color: #ffffff;
            font-size: 0.9rem;
            background: #1f2937;
        }

        .table tr:nth-child(even) td {
            background: #111827;
        }

        .table tr:nth-child(even):hover td {
            background: #374151;
        }

        .table tr:hover {
            background: #374151;
            transition: background-color 0.2s ease;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .product-image-tiny {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #4b5563;
        }

        .product-name-cell {
            font-weight: 500;
            color: #ffffff;
            font-size: 0.9rem;
        }

        .price-cell {
            font-weight: 600;
            color: #10b981;
            font-size: 1rem;
        }

        .status-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .date-cell {
            color: #9ca3af;
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
        }

        .table input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #f59e0b;
            cursor: pointer;
        }

        .table th:first-child,
        .table td:first-child {
            text-align: center;
            width: 60px;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            text-align: center;
            width: 50px;
        }

        .status-badge {
            background: #10b981;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .price-cell {
            font-weight: 600;
            color: #10b981;
        }

        .date-cell {
            color: #a0a0a0;
            font-size: 0.9rem;
        }


        /* Responsive Design */
        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .financial-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .financial-grid {
                grid-template-columns: 1fr;
            }

            .product-tabs {
                flex-wrap: wrap;
            }

            .tab-item {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date range functionality
            const fromDateInput = document.querySelector('input[type="date"]');
            const toDateInput = document.querySelectorAll('input[type="date"]')[1];

            // Set default dates
            const today = new Date();
            const oneMonthAgo = new Date();
            oneMonthAgo.setMonth(today.getMonth() - 1);

            if (fromDateInput && toDateInput) {
                fromDateInput.value = oneMonthAgo.toISOString().split('T')[0];
                toDateInput.value = today.toISOString().split('T')[0];

                // Add change event listeners
                fromDateInput.addEventListener('change', function() {
                    if (this.value && toDateInput.value && this.value > toDateInput.value) {
                        toDateInput.value = this.value;
                    }
                });

                toDateInput.addEventListener('change', function() {
                    if (this.value && fromDateInput.value && this.value < fromDateInput.value) {
                        fromDateInput.value = this.value;
                    }
                });
            }

            // Add click handlers for date inputs
            const dateInputGroups = document.querySelectorAll('.date-input-group');
            dateInputGroups.forEach(group => {
                group.addEventListener('click', function() {
                    const input = this.querySelector('input[type="date"]');
                    if (input) {
                        input.showPicker();
                    }
                });
            });
        });
    </script>
@endpush

@section('content')
    <div class="product-show-container">
        <!-- Product Header Section -->
        <div class="product-header">
            <div class="product-title-section">
                @if ($product->getFirstMedia('product_images'))
                    <img src="{{ $product->getFirstMedia('product_images')->getUrl() }}" alt="{{ $product->name }}"
                        class="product-image-small">
                @else
                    <div class="product-image-small"
                        style="background: #3a3a3a; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box-open" style="font-size: 2rem; color: #a0a0a0;"></i>
                    </div>
                @endif
                <div class="product-title-info">
                    <h1 class="product-title">{{ $product->getTranslation('name', app()->getLocale()) }}</h1>
                    <p class="product-subtitle">{{ $product->getTranslation('name', 'en') }}</p>
                </div>
                <div class="product-status-info">
                    <span class="status-info available">{{ __('Available') }}</span>
                    <span class="status-info api">{{ __('API') }}</span>
                </div>
            </div>

            <div class="product-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('Make sure to enter information correctly') }}
            </div>

            <!-- Key Metrics -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon up">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="metric-value">{{ number_format($maxQuantity) }}</div>
                    <div class="metric-label">{{ __('Max Quantity') }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon down">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="metric-value">{{ number_format($minQuantity) }}</div>
                    <div class="metric-label">{{ __('Min Quantity') }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon neutral">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="metric-value">{{ number_format($capital, 2) }}</div>
                    <div class="metric-label">{{ __('Capital') }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon neutral">
                        <i class="fas fa-th"></i>
                    </div>
                    <div class="metric-value">{{ number_format($salesQuantity) }}</div>
                    <div class="metric-label">{{ __('Sales Quantity') }}</div>
                </div>
            </div>

        </div>

        <!-- Navigation Tabs -->
        <div class="product-tabs">
            <button class="tab-item active">{{ __('Product Overview') }}</button>
            <button class="tab-item">{{ __('Product Settings') }}</button>
            <button class="tab-item">{{ __('Custom Prices') }}</button>
            <button class="tab-item">{{ __('Inventory') }}</button>
        </div>

        <!-- Overview Section -->
        <div class="overview-section">
            <div class="overview-header">
                <h2 class="overview-title">{{ __('Product Overview') }}</h2>
                <div class="date-range-picker">
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="date-input" id="fromDate">
                    </div>
                    <div class="date-separator">-</div>
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="date-input" id="toDate">
                    </div>
                </div>
            </div>

            <!-- Financial Summary Cards -->
            <div class="financial-summary-cards">
                <div class="financial-card net-profit">
                    <div class="financial-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="financial-content">
                        <div class="financial-value">${{ number_format($netProfit, 2) }}</div>
                        <div class="financial-label">{{ __('Net Profit') }}</div>
                    </div>
                </div>
                <div class="financial-card capital">
                    <div class="financial-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="financial-content">
                        <div class="financial-value">${{ number_format($capital, 2) }}</div>
                        <div class="financial-label">{{ __('Capital') }}</div>
                    </div>
                </div>
                <div class="financial-card sales-amount">
                    <div class="financial-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="financial-content">
                        <div class="financial-value">${{ number_format($totalSales, 2) }}</div>
                        <div class="financial-label">{{ __('Sales Amount') }}</div>
                    </div>
                </div>
                <div class="financial-card sales-quantity">
                    <div class="financial-icon">
                        <i class="fas fa-th"></i>
                    </div>
                    <div class="financial-content">
                        <div class="financial-value">{{ number_format($salesQuantity) }}</div>
                        <div class="financial-label">{{ __('Sales Quantity') }}</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Sales Table -->
        <div class="sales-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <input type="checkbox">
                        </th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $index => $orderItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><input type="checkbox"></td>
                            <td>
                                <div class="product-cell">
                                    @if ($product->getFirstMedia('product_images'))
                                        <img src="{{ $product->getFirstMedia('product_images')->getUrl() }}"
                                            alt="{{ $product->name }}" class="product-image-tiny">
                                    @else
                                        <div class="product-image-tiny"
                                            style="background: #3a3a3a; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-box-open" style="font-size: 1rem; color: #a0a0a0;"></i>
                                        </div>
                                    @endif
                                    <div class="product-name-cell">
                                        {{ $product->getTranslation('name', app()->getLocale()) }} /
                                        {{ $product->getTranslation('name', 'en') }}
                                    </div>
                                </div>
                            </td>
                            <td class="price-cell">${{ number_format($orderItem->product->price ?? 0, 4) }}</td>
                            <td><span class="status-badge">{{ __('Completed') }}</span></td>
                            <td class="date-cell">{{ $orderItem->created_at->format('H:i:s Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 2rem; color: #a0a0a0;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <br>
                                {{ __('No orders found for this product') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>

    <script>
        // Handle success message from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                if (typeof showSuccess === 'function') {
                    showSuccess('{{ __('Success') }}', '{{ __('Product updated successfully') }}');
                }
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Auto-hide notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            });

            // Auto-hide success message in header after 5 seconds
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
@endpush
