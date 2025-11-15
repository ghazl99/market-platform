@extends('core::dashboard.traditional.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Statistics'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="statistics-page">
        <div class="statistics-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-chart-line"></i>
                    {{ __('Statistics Dashboard') }}
                </h1>
                <div class="page-actions">
                    <button class="refresh-btn" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                        <i class="fas fa-sync-alt"></i>
                        {{ __('Refresh') }}
                    </button>
                </div>
            </div>

            <!-- Error/Success Messages -->
            @if (session('error'))
                <div class="notification notification-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="notification notification-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Date Range Filter -->
            <div class="date-filter-container">
                <form method="GET" action="{{ route('dashboard.statistics') }}" id="dateFilterForm"
                    class="date-filter-form">
                    <div class="date-filter-row">
                        <div class="date-filter-group">
                            <label class="date-filter-label">
                                <i class="fas fa-calendar-alt"></i>
                                {{ __('From Date') }}
                            </label>
                            <input type="date" name="from_date" id="from_date" class="date-filter-input"
                                value="{{ request('from_date', '') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="date-filter-group">
                            <label class="date-filter-label">
                                <i class="fas fa-calendar-check"></i>
                                {{ __('To Date') }}
                            </label>
                            <input type="date" name="to_date" id="to_date" class="date-filter-input"
                                value="{{ request('to_date', '') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="date-filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="fas fa-filter"></i>
                                {{ __('Filter') }}
                            </button>
                            @if (request('from_date') || request('to_date'))
                                <a href="{{ route('dashboard.statistics') }}" class="clear-filter-btn">
                                    <i class="fas fa-times"></i>
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Overview Cards -->
            <div class="stats-overview">
                <!-- Users Statistics -->
                <div class="stat-card users-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Total Customers') }}</h3>
                        <div class="stat-value">{{ number_format($totalUsers) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-check-circle"></i>
                                {{ __('Active') }}: {{ number_format($activeUsers) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-user-plus"></i>
                                {{ __('Today') }}: {{ $newUsersToday }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-calendar"></i>
                                {{ __('This Month') }}: {{ $newUsersThisMonth }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Orders Statistics -->
                <div class="stat-card orders-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Total Orders') }}</h3>
                        <div class="stat-value">{{ number_format($totalOrders) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-check text-success"></i>
                                {{ __('Completed') }}: {{ number_format($completedOrders) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-clock text-warning"></i>
                                {{ __('Pending') }}: {{ number_format($pendingOrders) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-hourglass-half text-info"></i>
                                {{ __('Confirmed') }}: {{ number_format($confirmedOrders ?? 0) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-times text-danger"></i>
                                {{ __('Cancelled') }}: {{ number_format($cancelledOrders) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Sales Statistics -->
                <div class="stat-card sales-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Total Sales') }}</h3>
                        <div class="stat-value">${{ number_format($totalSalesAmount, 2) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-sun"></i>
                                {{ __('Today') }}: ${{ number_format($todaySales, 2) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-calendar-alt"></i>
                                {{ __('This Month') }}: ${{ number_format($thisMonthSales, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Products Statistics -->
                <div class="stat-card products-card">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Total Products') }}</h3>
                        <div class="stat-value">{{ number_format($totalProducts) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-check-circle text-success"></i>
                                {{ __('Active') }}: {{ number_format($activeProducts) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-file-alt"></i>
                                {{ __('Draft') }}: {{ number_format($draftProducts) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-star text-warning"></i>
                                {{ __('Featured') }}: {{ number_format($featuredProducts) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Categories Statistics -->
                <div class="stat-card categories-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Categories') }}</h3>
                        <div class="stat-value">{{ number_format($totalCategories) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-check-circle text-success"></i>
                                {{ __('Active') }}: {{ number_format($activeCategories) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Wallet Statistics -->
                <div class="stat-card wallet-card">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Wallet Transactions') }}</h3>
                        <div class="stat-value">${{ number_format($totalDeposits - $totalWithdrawals, 2) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-arrow-down text-success"></i>
                                {{ __('Deposits') }}: ${{ number_format($totalDeposits, 2) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-arrow-up text-danger"></i>
                                {{ __('Withdrawals') }}: ${{ number_format($totalWithdrawals, 2) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-calendar-day"></i>
                                {{ __('Today Deposits') }}: ${{ number_format($todayDeposits, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Requests Statistics -->
                <div class="stat-card payment-requests-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-label">{{ __('Payment Requests') }}</h3>
                        <div class="stat-value">{{ number_format($totalPaymentRequests) }}</div>
                        <div class="stat-details">
                            <span class="stat-item">
                                <i class="fas fa-clock text-warning"></i>
                                {{ __('Pending') }}: {{ number_format($pendingPaymentRequests) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-check text-success"></i>
                                {{ __('Approved') }}: {{ number_format($approvedPaymentRequests) }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-times text-danger"></i>
                                {{ __('Rejected') }}: {{ number_format($rejectedPaymentRequests) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <!-- Activity Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-area"></i>
                            {{ __('Activity Last 7 Days') }}
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <!-- Orders by Status Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            {{ __('Orders by Status') }}
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="ordersStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Information Tables -->
            <div class="tables-section">
                <!-- Recent Orders -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i class="fas fa-shopping-bag"></i>
                            {{ __('Recent Orders') }}
                        </h3>
                        <a href="{{ route('dashboard.order.index') ?? '#' }}" class="view-all-link">
                            {{ __('View All') }}
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>
                                            @php
                                                $customerName = __('Guest');
                                                
                                                // التحقق من وجود user_id
                                                if ($order->user_id) {
                                                    // محاولة الحصول على المستخدم
                                                    $user = $order->user;
                                                    
                                                    // إذا لم تكن العلاقة محملة، حاول تحميلها
                                                    if (!$user && $order->user_id) {
                                                        try {
                                                            $user = \Modules\User\Models\User::find($order->user_id);
                                                        } catch (\Exception $e) {
                                                            $user = null;
                                                        }
                                                    }
                                                    
                                                    if ($user) {
                                                        $name = $user->name;
                                                        
                                                        // معالجة الاسم إذا كان array (translatable)
                                                        if (is_array($name)) {
                                                            $locale = app()->getLocale();
                                                            $customerName = $name[$locale] ?? $name['ar'] ?? $name['en'] ?? $user->email ?? __('Guest');
                                                        } 
                                                        // إذا كان الاسم string
                                                        elseif (is_string($name) && !empty(trim($name))) {
                                                            $customerName = $name;
                                                        } 
                                                        // إذا كان الاسم فارغ، استخدم البريد الإلكتروني
                                                        else {
                                                            $customerName = $user->email ?? __('Guest');
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <span title="User ID: {{ $order->user_id ?? 'N/A' }}">
                                                {{ $customerName }}
                                            </span>
                                        </td>
                                        <td class="amount">${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $order->status }}">
                                                @switch($order->status)
                                                    @case('completed')
                                                        {{ __('Completed') }}
                                                    @break

                                                    @case('pending')
                                                        {{ __('Pending') }}
                                                    @break

                                                    @case('confirmed')
                                                        {{ __('Confirmed') }}
                                                    @break

                                                    @case('processing')
                                                        {{ __('Processing') }}
                                                    @break

                                                    @case('cancelled')
                                                    @case('canceled')
                                                        {{ __('Cancelled') }}
                                                    @break

                                                    @default
                                                        {{ ucfirst($order->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-table">{{ __('No orders found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Customers -->
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">
                                <i class="fas fa-user-friends"></i>
                                {{ __('Recent Customers') }}
                            </h3>
                            <a href="{{ route('dashboard.customer.index') }}" class="view-all-link">
                                {{ __('View All') }}
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Registered') }}</th>
                                        <th>{{ __('Last Login') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? __('N/A') }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : __('Never') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-table">{{ __('No customers found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">
                                <i class="fas fa-star"></i>
                                {{ __('Top Selling Products') }}
                            </h3>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Rank') }}</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('Total Sold') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $product)
                                        <tr>
                                            <td>
                                                <span class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td class="amount">{{ number_format($product->total_sold ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="empty-table">{{ __('No products found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endsection

    @push('styles')
        <style>
            /* Statistics Page Styles */
            .statistics-page {
                padding: 2rem;
                background: var(--bg-secondary);
                min-height: calc(100vh - 80px);
                color: var(--text-primary);
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .statistics-container {
                max-width: 1400px;
                margin: 0 auto;
            }

            .page-header {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .page-title {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .page-title i {
                color: var(--primary-color);
                font-size: 2rem;
            }

            .page-actions {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .refresh-btn {
                background: var(--bg-secondary);
                color: var(--text-primary);
                border: 1px solid var(--border-color);
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .refresh-btn:hover {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            .refresh-btn:hover i {
                animation: spin 1s linear;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* Statistics Overview Cards */
            .stats-overview {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 1.5rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                display: flex;
                gap: 1.5rem;
                align-items: flex-start;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: var(--primary-color);
                transition: width 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-lg);
            }

            .stat-card:hover::before {
                width: 8px;
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

            .users-card .stat-icon {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                color: white;
            }

            .orders-card .stat-icon {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
            }

            .sales-card .stat-icon {
                background: linear-gradient(135deg, #f59e0b, #d97706);
                color: white;
            }

            .products-card .stat-icon {
                background: linear-gradient(135deg, #8b5cf6, #7c3aed);
                color: white;
            }

            .categories-card .stat-icon {
                background: linear-gradient(135deg, #ec4899, #db2777);
                color: white;
            }

            .wallet-card .stat-icon {
                background: linear-gradient(135deg, #14b8a6, #0d9488);
                color: white;
            }

            .payment-requests-card .stat-icon {
                background: linear-gradient(135deg, #6366f1, #4f46e5);
                color: white;
            }

            .stat-content {
                flex: 1;
            }

            .stat-label {
                font-size: 0.875rem;
                color: var(--text-secondary);
                font-weight: 600;
                margin: 0 0 0.5rem 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .stat-value {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 1rem;
                line-height: 1;
            }

            .stat-details {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .stat-item {
                font-size: 0.875rem;
                color: var(--text-secondary);
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .stat-item i {
                width: 16px;
                font-size: 0.75rem;
            }

            /* Charts Section */
            .charts-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .chart-card {
                background: var(--bg-primary);
                border-radius: 16px;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                overflow: hidden;
            }

            .chart-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--border-color);
            }

            .chart-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text-primary);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .chart-title i {
                color: var(--primary-color);
            }

            .chart-body {
                padding: 1.5rem;
                min-height: 300px;
            }

            /* Tables Section */
            .tables-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .table-card {
                background: var(--bg-primary);
                border-radius: 16px;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                overflow: hidden;
            }

            .table-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--border-color);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .table-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text-primary);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .table-title i {
                color: var(--primary-color);
            }

            .view-all-link {
                color: var(--primary-color);
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
            }

            .view-all-link:hover {
                color: var(--text-primary);
                gap: 0.75rem;
            }

            .table-container {
                overflow-x: auto;
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
            }

            .data-table th {
                background: var(--bg-secondary);
                color: var(--text-primary);
                padding: 1rem;
                text-align: right;
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 2px solid var(--border-color);
            }

            .data-table td {
                padding: 1rem;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-primary);
            }

            .data-table tbody tr:hover {
                background: var(--bg-secondary);
            }

            .data-table tbody tr:last-child td {
                border-bottom: none;
            }

            .status-badge {
                display: inline-block;
                padding: 0.375rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .status-badge.status-completed {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .status-badge.status-pending {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .status-badge.status-processing {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }

            .status-badge.status-cancelled {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }

            .amount {
                font-weight: 600;
                color: var(--primary-color);
            }

            .empty-table {
                text-align: center;
                padding: 3rem !important;
                color: var(--text-secondary);
            }

            .rank-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                font-weight: 700;
                font-size: 0.875rem;
            }

            .rank-badge.rank-1 {
                background: linear-gradient(135deg, #fbbf24, #f59e0b);
                color: white;
            }

            .rank-badge.rank-2 {
                background: linear-gradient(135deg, #94a3b8, #64748b);
                color: white;
            }

            .rank-badge.rank-3 {
                background: linear-gradient(135deg, #f97316, #ea580c);
                color: white;
            }

            .rank-badge.rank-other {
                background: var(--bg-secondary);
                color: var(--text-primary);
                border: 2px solid var(--border-color);
            }

            /* Light Mode Styles */
            html[data-theme="light"] .statistics-page,
            html[data-theme="light"] body .statistics-page {
                background: #f9fafb !important;
            }

            html[data-theme="light"] .page-header,
            html[data-theme="light"] body .page-header {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .page-title,
            html[data-theme="light"] body .page-title {
                color: #111827 !important;
            }

            html[data-theme="light"] .refresh-btn,
            html[data-theme="light"] body .refresh-btn {
                background: #f3f4f6 !important;
                color: #374151 !important;
                border: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .refresh-btn:hover,
            html[data-theme="light"] body .refresh-btn:hover {
                background: #059669 !important;
                color: #ffffff !important;
                border-color: #059669 !important;
            }

            html[data-theme="light"] .stat-card,
            html[data-theme="light"] body .stat-card {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
            }

            html[data-theme="light"] .stat-card:hover,
            html[data-theme="light"] body .stat-card:hover {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            }

            html[data-theme="light"] .stat-label,
            html[data-theme="light"] body .stat-label {
                color: #6b7280 !important;
            }

            html[data-theme="light"] .stat-value,
            html[data-theme="light"] body .stat-value {
                color: #111827 !important;
            }

            html[data-theme="light"] .stat-item,
            html[data-theme="light"] body .stat-item {
                color: #6b7280 !important;
            }

            html[data-theme="light"] .chart-card,
            html[data-theme="light"] body .chart-card {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .chart-header,
            html[data-theme="light"] body .chart-header {
                border-bottom: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .chart-title,
            html[data-theme="light"] body .chart-title {
                color: #111827 !important;
            }

            html[data-theme="light"] .table-card,
            html[data-theme="light"] body .table-card {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .table-header,
            html[data-theme="light"] body .table-header {
                border-bottom: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .table-title,
            html[data-theme="light"] body .table-title {
                color: #111827 !important;
            }

            html[data-theme="light"] .view-all-link,
            html[data-theme="light"] body .view-all-link {
                color: #059669 !important;
            }

            html[data-theme="light"] .view-all-link:hover,
            html[data-theme="light"] body .view-all-link:hover {
                color: #047857 !important;
            }

            html[data-theme="light"] .data-table th,
            html[data-theme="light"] body .data-table th {
                background: #f9fafb !important;
                color: #111827 !important;
                border-bottom: 2px solid #e5e7eb !important;
            }

            html[data-theme="light"] .data-table td,
            html[data-theme="light"] body .data-table td {
                border-bottom: 1px solid #e5e7eb !important;
                color: #111827 !important;
            }

            html[data-theme="light"] .data-table tbody tr:hover,
            html[data-theme="light"] body .data-table tbody tr:hover {
                background: #f9fafb !important;
            }

            html[data-theme="light"] .empty-table,
            html[data-theme="light"] body .empty-table {
                color: #6b7280 !important;
            }

            html[data-theme="light"] .amount,
            html[data-theme="light"] body .amount {
                color: #059669 !important;
            }

            html[data-theme="light"] .rank-badge.rank-other,
            html[data-theme="light"] body .rank-badge.rank-other {
                background: #f3f4f6 !important;
                color: #374151 !important;
                border: 2px solid #e5e7eb !important;
            }

            /* Dark Mode Styles - White Text */
            [data-theme="dark"] .statistics-page,
            html[data-theme="dark"] .statistics-page,
            html[data-theme="dark"] body .statistics-page {
                background: var(--bg-secondary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .page-header,
            html[data-theme="dark"] .page-header,
            html[data-theme="dark"] body .page-header {
                background: var(--bg-primary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .page-title,
            html[data-theme="dark"] .page-title,
            html[data-theme="dark"] body .page-title {
                color: #ffffff !important;
            }

            [data-theme="dark"] .stat-card,
            html[data-theme="dark"] .stat-card,
            html[data-theme="dark"] body .stat-card {
                background: var(--bg-primary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .stat-label,
            html[data-theme="dark"] .stat-label,
            html[data-theme="dark"] body .stat-label {
                color: #d1d5db !important;
            }

            [data-theme="dark"] .stat-value,
            html[data-theme="dark"] .stat-value,
            html[data-theme="dark"] body .stat-value {
                color: #ffffff !important;
            }

            [data-theme="dark"] .stat-item,
            html[data-theme="dark"] .stat-item,
            html[data-theme="dark"] body .stat-item {
                color: #d1d5db !important;
            }

            [data-theme="dark"] .chart-card,
            html[data-theme="dark"] .chart-card,
            html[data-theme="dark"] body .chart-card {
                background: var(--bg-primary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .chart-title,
            html[data-theme="dark"] .chart-title,
            html[data-theme="dark"] body .chart-title {
                color: #ffffff !important;
            }

            [data-theme="dark"] .table-card,
            html[data-theme="dark"] .table-card,
            html[data-theme="dark"] body .table-card {
                background: var(--bg-primary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .table-title,
            html[data-theme="dark"] .table-title,
            html[data-theme="dark"] body .table-title {
                color: #ffffff !important;
            }

            [data-theme="dark"] .data-table th,
            html[data-theme="dark"] .data-table th,
            html[data-theme="dark"] body .data-table th {
                background: var(--bg-secondary) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .data-table td,
            html[data-theme="dark"] .data-table td,
            html[data-theme="dark"] body .data-table td {
                color: #ffffff !important;
            }

            [data-theme="dark"] .view-all-link,
            html[data-theme="dark"] .view-all-link,
            html[data-theme="dark"] body .view-all-link {
                color: #10b981 !important;
            }

            [data-theme="dark"] .view-all-link:hover,
            html[data-theme="dark"] .view-all-link:hover,
            html[data-theme="dark"] body .view-all-link:hover {
                color: #34d399 !important;
            }

            [data-theme="dark"] .empty-table,
            html[data-theme="dark"] .empty-table,
            html[data-theme="dark"] body .empty-table {
                color: #9ca3af !important;
            }

            [data-theme="dark"] .amount,
            html[data-theme="dark"] .amount,
            html[data-theme="dark"] body .amount {
                color: #10b981 !important;
            }

            [data-theme="dark"] .status-badge,
            html[data-theme="dark"] .status-badge,
            html[data-theme="dark"] body .status-badge {
                color: inherit !important;
            }

            [data-theme="dark"] .refresh-btn,
            html[data-theme="dark"] .refresh-btn,
            html[data-theme="dark"] body .refresh-btn {
                background: var(--bg-secondary) !important;
                color: #ffffff !important;
                border-color: var(--border-color) !important;
            }

            [data-theme="dark"] .refresh-btn:hover,
            html[data-theme="dark"] .refresh-btn:hover,
            html[data-theme="dark"] body .refresh-btn:hover {
                background: var(--primary-color) !important;
                color: #ffffff !important;
            }

            /* Notification Styles */
            .notification {
                padding: 1rem 1.5rem;
                border-radius: 12px;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
                box-shadow: var(--shadow-md);
                animation: slideDown 0.3s ease;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .notification-error {
                background: rgba(239, 68, 68, 0.1);
                border: 1px solid rgba(239, 68, 68, 0.3);
                color: #ef4444;
            }

            .notification-success {
                background: rgba(16, 185, 129, 0.1);
                border: 1px solid rgba(16, 185, 129, 0.3);
                color: #10b981;
            }

            .notification i {
                font-size: 1.1rem;
            }

            html[data-theme="light"] .notification-error,
            html[data-theme="light"] body .notification-error {
                background: #fef2f2 !important;
                border-color: #fecaca !important;
                color: #dc2626 !important;
            }

            html[data-theme="light"] .notification-success,
            html[data-theme="light"] body .notification-success {
                background: #f0fdf4 !important;
                border-color: #bbf7d0 !important;
                color: #16a34a !important;
            }

            [data-theme="dark"] .notification-error,
            html[data-theme="dark"] .notification-error,
            html[data-theme="dark"] body .notification-error {
                background: rgba(239, 68, 68, 0.2) !important;
                border-color: rgba(239, 68, 68, 0.4) !important;
                color: #f87171 !important;
            }

            [data-theme="dark"] .notification-success,
            html[data-theme="dark"] .notification-success,
            html[data-theme="dark"] body .notification-success {
                background: rgba(16, 185, 129, 0.2) !important;
                border-color: rgba(16, 185, 129, 0.4) !important;
                color: #34d399 !important;
            }

            /* Date Filter Styles */
            .date-filter-container {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .date-filter-container::-webkit-scrollbar {
                display: none;
            }

            .date-filter-form {
                width: 100%;
                min-width: 0;
            }

            .date-filter-row {
                display: flex;
                gap: 1rem;
                align-items: flex-end;
                flex-wrap: nowrap;
            }

            .date-filter-group {
                flex: 1;
                min-width: 0;
            }

            .date-filter-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--text-secondary);
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .date-filter-label i {
                color: var(--primary-color);
                font-size: 0.9rem;
            }

            .date-filter-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                background: var(--bg-secondary);
                color: var(--text-primary);
                font-size: 1rem;
                transition: all 0.3s ease;
                box-sizing: border-box;
                min-width: 0;
            }

            .date-filter-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            }

            .date-filter-actions {
                display: flex;
                gap: 0.75rem;
                align-items: center;
            }

            .filter-btn {
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .filter-btn:hover {
                background: var(--primary-color-dark, #047857);
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
            }

            .clear-filter-btn {
                background: var(--bg-secondary);
                color: var(--text-primary);
                border: 1px solid var(--border-color);
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .clear-filter-btn:hover {
                background: var(--bg-primary);
                border-color: var(--primary-color);
                color: var(--primary-color);
            }

            /* Light Mode Styles for Date Filter */
            html[data-theme="light"] .date-filter-container,
            html[data-theme="light"] body .date-filter-container {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
            }

            html[data-theme="light"] .date-filter-label,
            html[data-theme="light"] body .date-filter-label {
                color: #6b7280 !important;
            }

            html[data-theme="light"] .date-filter-input,
            html[data-theme="light"] body .date-filter-input {
                background: #f9fafb !important;
                border: 1px solid #e5e7eb !important;
                color: #111827 !important;
            }

            html[data-theme="light"] .date-filter-input:focus,
            html[data-theme="light"] body .date-filter-input:focus {
                border-color: #059669 !important;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            }

            html[data-theme="light"] .clear-filter-btn,
            html[data-theme="light"] body .clear-filter-btn {
                background: #f3f4f6 !important;
                border: 1px solid #e5e7eb !important;
                color: #374151 !important;
            }

            html[data-theme="light"] .clear-filter-btn:hover,
            html[data-theme="light"] body .clear-filter-btn:hover {
                background: #ffffff !important;
                border-color: #059669 !important;
                color: #059669 !important;
            }

            /* Dark Mode Styles for Date Filter */
            [data-theme="dark"] .date-filter-container,
            html[data-theme="dark"] .date-filter-container,
            html[data-theme="dark"] body .date-filter-container {
                background: var(--bg-primary) !important;
                border: 1px solid var(--border-color) !important;
            }

            [data-theme="dark"] .date-filter-label,
            html[data-theme="dark"] .date-filter-label,
            html[data-theme="dark"] body .date-filter-label {
                color: #d1d5db !important;
            }

            [data-theme="dark"] .date-filter-input,
            html[data-theme="dark"] .date-filter-input,
            html[data-theme="dark"] body .date-filter-input {
                background: var(--bg-secondary) !important;
                border: 1px solid var(--border-color) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .date-filter-input:focus,
            html[data-theme="dark"] .date-filter-input:focus,
            html[data-theme="dark"] body .date-filter-input:focus {
                border-color: var(--primary-color) !important;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2) !important;
            }

            [data-theme="dark"] .clear-filter-btn,
            html[data-theme="dark"] .clear-filter-btn,
            html[data-theme="dark"] body .clear-filter-btn {
                background: var(--bg-secondary) !important;
                border: 1px solid var(--border-color) !important;
                color: #ffffff !important;
            }

            [data-theme="dark"] .clear-filter-btn:hover,
            html[data-theme="dark"] .clear-filter-btn:hover,
            html[data-theme="dark"] body .clear-filter-btn:hover {
                background: var(--bg-primary) !important;
                border-color: var(--primary-color) !important;
                color: var(--primary-color) !important;
            }

            /* Responsive Design for Date Filter */
            @media (max-width: 768px) {
                .date-filter-row {
                    gap: 0.75rem;
                    flex-wrap: nowrap;
                }

                .date-filter-group {
                    flex: 1;
                    min-width: 0;
                }

                .date-filter-label {
                    font-size: 0.8rem;
                    margin-bottom: 0.4rem;
                }

                .date-filter-input {
                    padding: 0.6rem 0.75rem;
                    font-size: 0.9rem;
                }

                .date-filter-actions {
                    flex-shrink: 0;
                    gap: 0.5rem;
                }

                .filter-btn,
                .clear-filter-btn {
                    padding: 0.6rem 1rem;
                    font-size: 0.85rem;
                    white-space: nowrap;
                }
            }

            @media (max-width: 480px) {
                .date-filter-row {
                    gap: 0.5rem;
                }

                .date-filter-label {
                    font-size: 0.75rem;
                    margin-bottom: 0.35rem;
                }

                .date-filter-input {
                    padding: 0.5rem 0.6rem;
                    font-size: 0.85rem;
                }

                .filter-btn,
                .clear-filter-btn {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.8rem;
                }

                .filter-btn i,
                .clear-filter-btn i {
                    display: none;
                }
            }

            /* Responsive Design */
            @media (max-width: 1200px) {
                .charts-section {
                    grid-template-columns: 1fr;
                }

                .tables-section {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .statistics-page {
                    padding: 1rem;
                }

                .stats-overview {
                    grid-template-columns: 1fr;
                }

                .stat-card {
                    flex-direction: column;
                    text-align: center;
                }

                .page-header {
                    flex-direction: column;
                    align-items: stretch;
                }

                .page-actions {
                    justify-content: center;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Date Filter Validation
                const dateFilterForm = document.getElementById('dateFilterForm');
                const fromDateInput = document.getElementById('from_date');
                const toDateInput = document.getElementById('to_date');

                if (dateFilterForm && fromDateInput && toDateInput) {
                    // Validate dates on form submit
                    dateFilterForm.addEventListener('submit', function(e) {
                        const fromDate = fromDateInput.value;
                        const toDate = toDateInput.value;

                        if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
                            e.preventDefault();
                            alert('{{ __('From date must be before to date') }}');
                            return false;
                        }
                    });

                    // Validate dates on input change
                    function validateDates() {
                        const fromDate = fromDateInput.value;
                        const toDate = toDateInput.value;

                        if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
                            toDateInput.setCustomValidity('{{ __('To date must be after from date') }}');
                        } else {
                            toDateInput.setCustomValidity('');
                        }
                    }

                    fromDateInput.addEventListener('change', validateDates);
                    toDateInput.addEventListener('change', validateDates);
                }

                // Get theme dynamically - check every time
                function getTheme() {
                    return document.documentElement.getAttribute('data-theme') || 'dark';
                }

                // Store chart instances
                let activityChart = null;
                let ordersStatusChart = null;

                // Function to update chart colors
                function updateChartTheme(chart) {
                    if (!chart) return;
                    const theme = getTheme();
                    const isDark = theme === 'dark';

                    // Update legend colors
                    if (chart.options.plugins.legend.labels) {
                        chart.options.plugins.legend.labels.color = isDark ? '#ffffff' : '#111827';
                    }

                    // Update tooltip colors
                    if (chart.options.plugins.tooltip) {
                        chart.options.plugins.tooltip.backgroundColor = isDark ? 'rgba(0, 0, 0, 0.8)' :
                            'rgba(255, 255, 255, 0.95)';
                        chart.options.plugins.tooltip.titleColor = isDark ? '#ffffff' : '#111827';
                        if (chart.options.plugins.tooltip.bodyColor) {
                            chart.options.plugins.tooltip.bodyColor = isDark ? '#ffffff' : '#111827';
                        }
                        chart.options.plugins.tooltip.borderColor = isDark ? 'rgba(255, 255, 255, 0.1)' :
                            'rgba(0, 0, 0, 0.1)';
                    }

                    // Update scales colors
                    if (chart.options.scales) {
                        Object.keys(chart.options.scales).forEach(key => {
                            const scale = chart.options.scales[key];
                            if (scale.grid) {
                                scale.grid.color = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                            }
                            if (scale.ticks) {
                                scale.ticks.color = isDark ? '#ffffff' : '#111827';
                            }
                        });
                    }

                    chart.update();
                }

                const theme = getTheme();
                if (theme === 'light') {
                    document.body.offsetHeight;
                    document.documentElement.setAttribute('data-theme', 'light');
                }

                // Activity Chart
                const activityCtx = document.getElementById('activityChart');
                if (activityCtx) {
                    // تحضير البيانات
                    const last7DaysData = @json($last7Days);
                    const labels = last7DaysData.map(item => item.day_name || item.date_label || '');
                    const ordersData = last7DaysData.map(item => parseInt(item.orders) || 0);
                    const salesData = last7DaysData.map(item => parseFloat(item.sales) || 0);
                    const usersData = last7DaysData.map(item => parseInt(item.users) || 0);
                    
                    // Debug: طباعة البيانات في console
                    console.log('Activity Chart Data:', {
                        labels: labels,
                        orders: ordersData,
                        sales: salesData,
                        users: usersData
                    });
                    
                    activityChart = new Chart(activityCtx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: '{{ __('Orders') }}',
                                    data: ordersData,
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                },
                                {
                                    label: '{{ __('Sales') }} ($)',
                                    data: salesData,
                                    borderColor: 'rgb(16, 185, 129)',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    yAxisID: 'y1',
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                },
                                {
                                    label: '{{ __('New Users') }}',
                                    data: usersData,
                                    borderColor: 'rgb(245, 158, 11)',
                                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                        font: {
                                            size: 12,
                                            weight: 'normal'
                                        },
                                        padding: 15,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: getTheme() === 'dark' ? 'rgba(0, 0, 0, 0.8)' :
                                        'rgba(255, 255, 255, 0.95)',
                                    titleColor: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                    bodyColor: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                    borderColor: getTheme() === 'dark' ? 'rgba(255, 255, 255, 0.1)' :
                                        'rgba(0, 0, 0, 0.1)',
                                    borderWidth: 1
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: getTheme() === 'dark' ? 'rgba(255, 255, 255, 0.1)' :
                                            'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: getTheme() === 'dark' ? 'rgba(255, 255, 255, 0.1)' :
                                            'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1,
                                        callback: function(value) {
                                            return Number.isInteger(value) ? value : '';
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: {
                                        drawOnChartArea: false
                                    },
                                    ticks: {
                                        color: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                        font: {
                                            size: 11
                                        },
                                        callback: function(value) {
                                            return '$' + Number(value).toFixed(0);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Orders Status Chart
                const ordersStatusCtx = document.getElementById('ordersStatusChart');
                if (ordersStatusCtx) {
                    const ordersStatusData = {
                        labels: [
                            '{{ __('Completed') }}',
                            '{{ __('Pending') }}',
                            '{{ __('Processing') }}',
                            '{{ __('Cancelled') }}'
                        ],
                        datasets: [{
                            data: [
                                {{ $ordersByStatus['completed'] ?? 0 }},
                                {{ $ordersByStatus['pending'] ?? 0 }},
                                {{ $ordersByStatus['processing'] ?? 0 }},
                                {{ $ordersByStatus['cancelled'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderColor: [
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(59, 130, 246)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 2
                        }]
                    };

                    ordersStatusChart = new Chart(ordersStatusCtx, {
                        type: 'doughnut',
                        data: ordersStatusData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                        padding: 15,
                                        font: {
                                            size: 12,
                                            weight: 'normal'
                                        },
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    backgroundColor: getTheme() === 'dark' ? 'rgba(0, 0, 0, 0.8)' :
                                        'rgba(255, 255, 255, 0.95)',
                                    titleColor: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                    bodyColor: getTheme() === 'dark' ? '#ffffff' : '#111827',
                                    borderColor: getTheme() === 'dark' ? 'rgba(255, 255, 255, 0.1)' :
                                        'rgba(0, 0, 0, 0.1)',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed !== null) {
                                                label += context.parsed;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Listen for theme changes
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                            updateChartTheme(activityChart);
                            updateChartTheme(ordersStatusChart);
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-theme']
                });
            });
        </script>
    @endpush
