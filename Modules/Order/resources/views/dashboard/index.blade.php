@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Orders'))

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

        @if (request()->get('success'))
            <div class="notification success professional-notification">
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
                    <div class="notification-message">{{ urldecode(request()->get('success')) }}</div>
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

    <div class="orders-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Manage Orders') }}</h1>
            <div class="page-actions">
                <a href="#" class="export-btn">
                    <i class="fas fa-download"></i>
                    {{ __('Export Data') }}
                </a>
            </div>
        </div>

        <!-- Orders Statistics -->
        <div class="orders-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number">{{ $orders->total() }}</div>
                <div class="stat-label">{{ __('Total Orders') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
                <div class="stat-label">{{ __('Pending') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $orders->where('status', 'completed')->count() }}</div>
                <div class="stat-label">{{ __('Completed') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon cancelled">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number">{{ $orders->where('status', 'canceled')->count() }}</div>
                <div class="stat-label">{{ __('Cancelled') }}</div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-filter-content">
                <div class="search-box">
                    <input type="text" placeholder="{{ __('Search orders...') }}" id="searchInput">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="processing">{{ __('Processing') }}</option>
                    <option value="canceled">{{ __('Cancelled') }}</option>
                </select>
                <select class="filter-select" id="periodFilter">
                    <option value="">{{ __('All Periods') }}</option>
                    <option value="today">{{ __('Today') }}</option>
                    <option value="week">{{ __('This Week') }}</option>
                    <option value="month">{{ __('This Month') }}</option>
                    <option value="year">{{ __('This Year') }}</option>
                </select>
                <select class="filter-select" id="amountFilter">
                    <option value="">{{ __('All Amounts') }}</option>
                    <option value="0-100">0 - 100 {{ __('SAR') }}</option>
                    <option value="100-500">100 - 500 {{ __('SAR') }}</option>
                    <option value="500-1000">500 - 1000 {{ __('SAR') }}</option>
                    <option value="1000+">{{ __('More than') }} 1000 {{ __('SAR') }}</option>
                </select>
                <button class="filter-btn" id="applyFilters">
                    <i class="fas fa-filter"></i>
                    {{ __('Apply') }}
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="order-id">#{{ $order->id }}</span></td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">{{ substr($order->user->name ?? 'U', 0, 1) }}</div>
                                    <div class="customer-details">
                                        <h6>{{ $order->user->name ?? __('Guest') }}</h6>
                                        <p>{{ $order->user->email ?? __('No email') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td><span class="order-amount">{{ number_format($order->total_amount, 2) }}
                                    {{ __('SAR') }}</span></td>
                            <td>
                                <span class="status-badge {{ $order->status }}">
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

                                        @default
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('dashboard.order.show', $order->id) }}" class="action-btn view">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('dashboard.order.edit', $order->id) }}" class="action-btn edit">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('dashboard.order.destroy', $order->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn delete delete-order-btn"
                                            data-order-id="{{ $order->id }}"
                                            data-customer-name="{{ $order->user->name ?? __('Guest') }}">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem;">
                                    <div style="color: #666; font-size: 1.1rem;">
                                        <i class="fas fa-shopping-cart"
                                            style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                        {{ __('No orders found') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="pagination">
                    @if ($orders->onFirstPage())
                        <button class="pagination-btn" disabled>
                            <i class="fas fa-chevron-right"></i>
                            {{ __('Previous') }}
                        </button>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                            {{ __('Previous') }}
                        </a>
                    @endif

                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if ($page == $orders->currentPage())
                            <button class="pagination-btn active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="pagination-btn">
                            {{ __('Next') }}
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @else
                        <button class="pagination-btn" disabled>
                            {{ __('Next') }}
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Custom Delete Confirmation Modal -->
        <div id="deleteModal" class="delete-modal" style="display: none;">
            <div class="delete-modal-overlay"></div>
            <div class="delete-modal-content">
                <div class="delete-modal-header">
                    <h3>{{ __('Confirm Delete') }}</h3>
                    <button type="button" class="delete-modal-close">&times;</button>
                </div>
                <div class="delete-modal-body">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p class="delete-message">
                        {{ __('Are you sure you want to delete the order') }} <strong id="orderIdToDelete"></strong>
                        {{ __('for customer') }} <strong id="customerNameToDelete"></strong>?
                    </p>
                    <p class="delete-warning">
                        {{ __('This action cannot be undone.') }}
                    </p>
                </div>
                <div class="delete-modal-footer">
                    <button type="button" class="btn btn-secondary delete-modal-cancel">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger delete-modal-confirm">
                        <i class="fas fa-trash"></i>
                        {{ __('Delete Order') }}
                    </button>
                </div>
            </div>
        </div>

        <style>
            /* Orders Page Specific Styles */
            .orders-container {
                padding: 2rem;
                background: var(--bg-secondary);
                min-height: 100vh;
                transition: background-color 0.3s ease;
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
                color: var(--text-primary);
                margin: 0;
                transition: color 0.3s ease;
            }

            .page-actions {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .export-btn {
                background: var(--bg-primary);
                color: var(--text-secondary);
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
                text-decoration: none;
            }

            .export-btn:hover {
                background: #059669;
                color: white;
                border-color: #059669;
            }

            .orders-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: var(--bg-primary);
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                text-align: center;
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: white;
                margin: 0 auto 1rem;
            }

            .stat-icon.total {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
            }

            .stat-icon.pending {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            }

            .stat-icon.completed {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            }

            .stat-icon.cancelled {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0 0 0.5rem 0;
                transition: color 0.3s ease;
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--text-secondary);
                margin: 0;
                transition: color 0.3s ease;
            }

            .search-filter-bar {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                transition: all 0.3s ease;
            }

            .search-filter-content {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr auto;
                gap: 1rem;
                align-items: end;
            }

            .search-box {
                position: relative;
            }

            .search-box input {
                width: 100%;
                padding: 0.75rem 1rem;
                padding-right: 3rem;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                background: var(--bg-secondary);
                color: var(--text-primary);
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .search-box input:focus {
                outline: none;
                border-color: #059669;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            }

            .search-box i {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #666666;
            }

            .filter-select {
                padding: 0.75rem 1rem;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                background: var(--bg-secondary);
                color: var(--text-primary);
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .filter-select:focus {
                outline: none;
                border-color: #059669;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            }

            .filter-btn {
                background: #059669;
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
                background: #047857;
                transform: translateY(-1px);
            }

            .orders-table-container {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 1.5rem;
                box-shadow: var(--shadow-md);
                border: 1px solid var(--border-color);
                overflow-x: auto;
                transition: all 0.3s ease;
            }

            .orders-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .orders-table th {
                background: var(--bg-secondary);
                padding: 1rem;
                font-weight: 600;
                text-align: right;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-primary);
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .orders-table td {
                padding: 1rem;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-secondary);
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .orders-table tr:hover {
                background: var(--bg-secondary);
            }

            .orders-table tr:nth-child(even) {
                background: rgba(0, 0, 0, 0.05);
            }

            /* Light Mode Styles */
            [data-theme="light"] .orders-container {
                background: #ffffff;
            }

            [data-theme="light"] .stat-card {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }

            [data-theme="light"] .stat-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            [data-theme="light"] .search-filter-bar {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }

            [data-theme="light"] .search-box input {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                color: #111827;
            }

            [data-theme="light"] .filter-select {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                color: #111827;
            }

            [data-theme="light"] .orders-table-container {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }

            [data-theme="light"] .orders-table th {
                background: #f9fafb;
                border-bottom: 1px solid #e5e7eb;
                color: #111827;
            }

            [data-theme="light"] .orders-table td {
                border-bottom: 1px solid #e5e7eb;
                color: #374151;
            }

            [data-theme="light"] .orders-table tr:hover {
                background: #f9fafb;
            }

            [data-theme="light"] .orders-table tr:nth-child(even) {
                background: #f9fafb;
            }

            [data-theme="light"] .export-btn {
                background: #ffffff;
                color: #374151;
                border: 1px solid #e5e7eb;
            }

            [data-theme="light"] .export-btn:hover {
                background: #059669;
                color: #ffffff;
            }

            [data-theme="light"] .pagination-btn {
                background: #ffffff;
                color: #374151;
                border: 1px solid #e5e7eb;
            }

            [data-theme="light"] .delete-modal-content {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }

            [data-theme="light"] .delete-modal-header {
                border-bottom: 1px solid #e5e7eb;
            }

            /* Dark Mode Styles */
            [data-theme="dark"] .orders-container {
                background: #111827;
            }

            [data-theme="dark"] .stat-card {
                background: #1f2937;
                border: 1px solid #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            }

            [data-theme="dark"] .stat-card:hover {
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            }

            [data-theme="dark"] .search-filter-bar {
                background: #1f2937;
                border: 1px solid #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            }

            [data-theme="dark"] .search-box input {
                background: #1f2937;
                border: 1px solid #374151;
                color: #f9fafb;
            }

            [data-theme="dark"] .filter-select {
                background: #1f2937;
                border: 1px solid #374151;
                color: #f9fafb;
            }

            [data-theme="dark"] .orders-table-container {
                background: #1f2937;
                border: 1px solid #374151;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            }

            [data-theme="dark"] .orders-table th {
                background: #111827;
                border-bottom: 1px solid #374151;
                color: #f9fafb;
            }

            [data-theme="dark"] .orders-table td {
                border-bottom: 1px solid #374151;
                color: #d1d5db;
            }

            [data-theme="dark"] .orders-table tr:hover {
                background: #111827;
            }

            [data-theme="dark"] .orders-table tr:nth-child(even) {
                background: rgba(0, 0, 0, 0.2);
            }

            [data-theme="dark"] .export-btn {
                background: #1f2937;
                color: #d1d5db;
                border: 1px solid #374151;
            }

            [data-theme="dark"] .delete-modal-content {
                background: #1f2937;
                border: 1px solid #374151;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            }

            [data-theme="dark"] .delete-modal-header {
                border-bottom: 1px solid #374151;
            }

            .order-id {
                font-weight: 600;
                color: #059669;
            }

            .customer-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .customer-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 0.9rem;
            }

            .customer-details h6 {
                margin: 0;
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-primary);
                transition: color 0.3s ease;
            }

            .customer-details p {
                margin: 0;
                font-size: 0.8rem;
                color: var(--text-secondary);
                transition: color 0.3s ease;
            }

            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .status-badge.completed {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .status-badge.pending {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .status-badge.canceled {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
            }

            .status-badge.processing {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }

            .order-amount {
                font-weight: 600;
                color: var(--text-primary);
                transition: color 0.3s ease;
            }

            .action-buttons {
                display: flex;
                gap: 0.5rem;
            }

            .action-btn {
                padding: 0.4rem 0.8rem;
                border: none;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.25rem;
                text-decoration: none;
            }

            .action-btn.view {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
                border: 1px solid rgba(59, 130, 246, 0.2);
            }

            .action-btn.view:hover {
                background: #3b82f6;
                color: white;
            }

            .action-btn.edit {
                background: var(--bg-primary);
                color: var(--text-secondary);
                border: 1px solid var(--border-color);
            }

            .action-btn.edit:hover {
                background: #059669;
                color: white;
                border-color: #059669;
            }

            [data-theme="light"] .action-btn.edit {
                background: #ffffff;
                color: #374151;
                border: 1px solid #e5e7eb;
            }

            [data-theme="dark"] .action-btn.edit {
                background: #1f2937;
                color: #d1d5db;
                border: 1px solid #374151;
            }

            .action-btn.delete {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            .action-btn.delete:hover {
                background: #ef4444;
                color: white;
            }

            .pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 0.5rem;
                margin-top: 2rem;
            }

            .pagination-btn {
                padding: 0.5rem 1rem;
                border: 1px solid var(--border-color);
                background: var(--bg-primary);
                color: var(--text-secondary);
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0.9rem;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .pagination-btn:hover {
                background: #059669;
                color: white;
                border-color: #059669;
            }

            .pagination-btn.active {
                background: #059669;
                color: white;
                border-color: #059669;
            }

            .pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* Custom Delete Modal Styles */
            .delete-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                font-family: 'Cairo', sans-serif;
            }

            .delete-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
            }

            .delete-modal-content {
                background: var(--bg-primary);
                border-radius: 16px;
                padding: 0;
                max-width: 500px;
                width: 90%;
                position: relative;
                box-shadow: var(--shadow-xl);
                border: 1px solid var(--border-color);
                animation: modalSlideIn 0.3s ease-out;
                transition: all 0.3s ease;
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

            .delete-modal-header {
                padding: 1.5rem 2rem 1rem;
                border-bottom: 1px solid var(--border-color);
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: border-color 0.3s ease;
            }

            .delete-modal-header h3 {
                color: var(--text-primary);
                margin: 0;
                font-size: 1.25rem;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .delete-modal-close {
                background: none;
                border: none;
                color: #888888;
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

            .delete-modal-close:hover {
                background: #404040;
                color: #ffffff;
            }

            .delete-modal-body {
                padding: 2rem;
                text-align: center;
            }

            .delete-icon {
                margin-bottom: 1rem;
            }

            .delete-icon i {
                font-size: 3rem;
                color: #f59e0b;
            }

            .delete-message {
                color: var(--text-primary);
                font-size: 1.1rem;
                margin-bottom: 1rem;
                line-height: 1.5;
                transition: color 0.3s ease;
            }

            .delete-warning {
                color: var(--text-light);
                font-size: 0.9rem;
                margin-bottom: 0;
                transition: color 0.3s ease;
            }

            .delete-modal-footer {
                padding: 1rem 2rem 1.5rem;
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
            }

            .btn {
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
            }

            .btn-secondary {
                background: #6b7280;
                color: #ffffff;
            }

            .btn-secondary:hover {
                background: #4b5563;
                transform: translateY(-1px);
            }

            .btn-danger {
                background: #ef4444;
                color: #ffffff;
            }

            .btn-danger:hover {
                background: #dc2626;
                transform: translateY(-1px);
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .search-filter-content {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }

                .orders-stats {
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                }
            }

            @media (max-width: 768px) {
                .orders-container {
                    padding: 1rem;
                }

                .page-header {
                    flex-direction: column;
                    align-items: stretch;
                }

                .page-actions {
                    justify-content: space-between;
                }

                .orders-stats {
                    grid-template-columns: repeat(2, 1fr);
                }

                .orders-table-container {
                    padding: 1rem;
                }

                .orders-table th,
                .orders-table td {
                    padding: 0.75rem 0.5rem;
                    font-size: 0.8rem;
                }

                .action-buttons {
                    flex-direction: column;
                }

                .delete-modal-content {
                    width: 95%;
                    margin: 1rem;
                }

                .delete-modal-header,
                .delete-modal-body,
                .delete-modal-footer {
                    padding: 1rem;
                }

                .delete-modal-footer {
                    flex-direction: column;
                }

                .btn {
                    width: 100%;
                    justify-content: center;
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
                    // Show notification with animation
                    setTimeout(() => {
                        notification.classList.add('show');
                    }, 100);

                    // Auto-hide after 5 seconds (6 seconds for professional notifications)
                    const hideDelay = notification.classList.contains('professional-notification') ? 6000 :
                        5000;
                    setTimeout(() => {
                        notification.classList.add('hide');
                        setTimeout(() => {
                            notification.remove();
                        }, 400);
                    }, hideDelay);

                    // Progress bar animation
                    const progressBar = notification.querySelector('.notification-progress');
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        progressBar.style.transition = `width ${hideDelay/1000}s linear`;
                    }
                });

                // Clean URL after showing notification
                if (window.location.search.includes('success=')) {
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    window.history.replaceState({}, document.title, url.pathname + url.search);
                }

                // Search functionality
                const searchInput = document.getElementById('searchInput');
                const ordersTable = document.querySelector('.orders-table tbody');

                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const query = e.target.value.toLowerCase();
                        filterOrders(query);
                    });
                }

                // Filter functionality
                const filterBtn = document.getElementById('applyFilters');
                if (filterBtn) {
                    filterBtn.addEventListener('click', function() {
                        applyFilters();
                    });
                }

                // Handle delete confirmation with custom modal
                let currentDeleteForm = null;
                let currentDeleteBtn = null;

                document.querySelectorAll('.delete-order-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const orderId = this.getAttribute('data-order-id');
                        const customerName = this.getAttribute('data-customer-name');
                        const form = this.closest('form');

                        // Store references for later use
                        currentDeleteForm = form;
                        currentDeleteBtn = this;

                        // Update modal content
                        document.getElementById('orderIdToDelete').textContent = '#' + orderId;
                        document.getElementById('customerNameToDelete').textContent = customerName;

                        // Show modal
                        document.getElementById('deleteModal').style.display = 'flex';
                    });
                });

                // Handle modal close
                document.querySelector('.delete-modal-close').addEventListener('click', function() {
                    document.getElementById('deleteModal').style.display = 'none';
                    currentDeleteForm = null;
                    currentDeleteBtn = null;
                });

                // Handle modal cancel
                document.querySelector('.delete-modal-cancel').addEventListener('click', function() {
                    document.getElementById('deleteModal').style.display = 'none';
                    currentDeleteForm = null;
                    currentDeleteBtn = null;
                });

                // Handle modal overlay click
                document.querySelector('.delete-modal-overlay').addEventListener('click', function() {
                    document.getElementById('deleteModal').style.display = 'none';
                    currentDeleteForm = null;
                    currentDeleteBtn = null;
                });

                // Handle delete confirmation
                document.querySelector('.delete-modal-confirm').addEventListener('click', function() {
                    if (currentDeleteForm && currentDeleteBtn) {
                        const originalText = currentDeleteBtn.innerHTML;
                        const orderId = currentDeleteBtn.getAttribute('data-order-id');

                        // Show loading state
                        currentDeleteBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> {{ __('Deleting...') }}';
                        currentDeleteBtn.disabled = true;

                        // Hide modal
                        document.getElementById('deleteModal').style.display = 'none';

                        // Send AJAX delete request
                        fetch(currentDeleteForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-HTTP-Method-Override': 'DELETE'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Show success notification
                                    showSuccessNotification(data.message, orderId);

                                    // Remove the row from table
                                    const row = currentDeleteBtn.closest('tr');
                                    if (row) {
                                        row.style.transition = 'all 0.3s ease';
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(-100%)';
                                        setTimeout(() => {
                                            row.remove();
                                        }, 300);
                                    }
                                } else {
                                    showErrorNotification(data.message);
                                    // Restore button state
                                    currentDeleteBtn.innerHTML = originalText;
                                    currentDeleteBtn.disabled = false;
                                }
                            })
                            .catch(error => {
                                showErrorNotification(
                                    '{{ __('An error occurred while deleting the order') }}');
                                // Restore button state
                                currentDeleteBtn.innerHTML = originalText;
                                currentDeleteBtn.disabled = false;
                            });
                    }
                });
            });

            function filterOrders(query) {
                const rows = document.querySelectorAll('.orders-table tbody tr');
                rows.forEach(row => {
                    const orderId = row.querySelector('.order-id').textContent.toLowerCase();
                    const customerName = row.querySelector('.customer-details h6').textContent.toLowerCase();

                    if (orderId.includes(query) || customerName.includes(query)) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function applyFilters() {
                const statusFilter = document.getElementById('statusFilter').value;
                const periodFilter = document.getElementById('periodFilter').value;
                const amountFilter = document.getElementById('amountFilter').value;

                console.log('Applying filters:', {
                    statusFilter,
                    periodFilter,
                    amountFilter
                });
                // Implement filter logic here
            }

            // Professional Success Notification
            function showSuccessNotification(message, orderId = null, action = 'deleted') {
                const notification = document.createElement('div');
                notification.className = 'notification success professional-notification';
                notification.innerHTML = `
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ __('Success') }}</div>
                        <div class="notification-message">${message}</div>
                        ${orderId ? `
                                                                        <div class="notification-details">
                                                                            <i class="fas fa-info-circle"></i>
                                                                            {{ __('Order') }} #${orderId} ${action === 'deleted' ? '{{ __('has been permanently deleted') }}' : action === 'updated' ? '{{ __('has been updated successfully') }}' : '{{ __('has been created successfully') }}'}
                                                                        </div>
                                                                        ` : ''}
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                    <div class="notification-progress"></div>
                `;

                document.querySelector('.notification-container').appendChild(notification);

                // Show notification with animation
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Auto-hide after 6 seconds
                setTimeout(() => {
                    notification.classList.add('hide');
                    setTimeout(() => {
                        notification.remove();
                    }, 400);
                }, 6000);

                // Progress bar animation
                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 6s linear';
                }
            }

            // Professional Error Notification
            function showErrorNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'notification error professional-notification';
                notification.innerHTML = `
                    <div class="notification-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ __('Error') }}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                    <div class="notification-progress"></div>
                `;

                document.querySelector('.notification-container').appendChild(notification);

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
            }
        </script>

        <style>
            /* Professional Notification Styles */
            .professional-notification {
                border-left: 4px solid;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
            }

            .professional-notification.success {
                border-left-color: #10b981;
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            }

            .professional-notification.error {
                border-left-color: #ef4444;
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            }

            .notification-details {
                margin-top: 0.5rem;
                padding: 0.5rem;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 6px;
                font-size: 0.85rem;
                color: #cccccc;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .notification-details i {
                color: #059669;
                font-size: 0.9rem;
            }

            .professional-notification .notification-icon i {
                font-size: 1.5rem;
                animation: successPulse 0.6s ease-out;
            }

            @keyframes successPulse {
                0% {
                    transform: scale(0.8);
                    opacity: 0.7;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 1;
                }

                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .professional-notification.show {
                animation: professionalSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            @keyframes professionalSlideIn {
                0% {
                    opacity: 0;
                    transform: translateX(100%) scale(0.8);
                }

                100% {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
            }
        </style>
    @endpush
