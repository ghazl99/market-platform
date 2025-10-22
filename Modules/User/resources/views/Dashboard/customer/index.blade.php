@extends('core::dashboard.layouts.app')

@section('title', __('Customers List'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/notifications.css') }}" rel="stylesheet">

    <style>
        /* Professional Notification System */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .notification {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            margin-bottom: 15px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-right: 4px solid;
            position: relative;
            overflow: hidden;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.hide {
            transform: translateX(100%);
            opacity: 0;
        }

        .notification.success {
            border-right-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        }

        .notification.error {
            border-right-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        .notification.warning {
            border-right-color: #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
        }

        .notification.info {
            border-right-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        }

        .notification-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .notification.success .notification-icon {
            background: #10b981;
            color: white;
        }

        .notification.error .notification-icon {
            background: #ef4444;
            color: white;
        }

        .notification.warning .notification-icon {
            background: #f59e0b;
            color: white;
        }

        .notification.info .notification-icon {
            background: #3b82f6;
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .notification.success .notification-title {
            color: #065f46;
        }

        .notification.error .notification-title {
            color: #991b1b;
        }

        .notification.warning .notification-title {
            color: #92400e;
        }

        .notification.info .notification-title {
            color: #1e40af;
        }

        .notification-message {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }

        .notification-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 18px;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .notification-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #374151;
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 0 0 12px 12px;
            transition: width 0.1s linear;
        }

        .notification.success .notification-progress {
            background: #10b981;
        }

        .notification.error .notification-progress {
            background: #ef4444;
        }

        .notification.warning .notification-progress {
            background: #f59e0b;
        }

        .notification.info .notification-progress {
            background: #3b82f6;
        }

        /* RTL Support */
        [dir="rtl"] .notification-container {
            right: auto;
            left: 20px;
        }

        [dir="rtl"] .notification {
            border-right: none;
            border-left: 4px solid;
        }

        [dir="rtl"] .notification-close {
            right: auto;
            left: 10px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .notification-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .notification {
                padding: 15px;
                margin-bottom: 10px;
            }

            .notification-title {
                font-size: 15px;
            }

            .notification-message {
                font-size: 13px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="users-container">
        <!-- Professional Notifications -->
        <div class="notification-container">
            @if (session('success'))
                <div class="notification success professional-notification show">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
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
                <div class="notification error professional-notification show">
                    <div class="notification-icon">
                        <i class="fas fa-exclamation-circle"></i>
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
                <div class="notification warning professional-notification show">
                    <div class="notification-icon">
                        <i class="fas fa-exclamation-triangle"></i>
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
                <div class="notification info professional-notification show">
                    <div class="notification-icon">
                        <i class="fas fa-info-circle"></i>
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
                <div class="notification error professional-notification show">
                    <div class="notification-icon">
                        <i class="fas fa-exclamation-circle"></i>
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

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Customers List') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.customer.create') }}" class="add-user-btn">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Customer') }}
                </a>
                <a href="{{ route('dashboard') }}" class="add-user-btn"
                    style="background: var(--bg-secondary, #f9fafb); color: var(--text-secondary, #6b7280); border: 1px solid var(--border-color, #e5e7eb);">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        <!-- Customers Statistics -->
        <div class="users-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $users->total() }}</div>
                <div class="stat-label">{{ __('Total Customers') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
                <div class="stat-label">{{ __('Active') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon new">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-number">{{ $users->where('created_at', '>=', now()->subWeek())->count() }}</div>
                <div class="stat-label">{{ __('Active This Week') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon premium">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number">{{ $users->where('last_login_at', '>=', now()->subDay())->count() }}</div>
                <div class="stat-label">{{ __('Recent Activity') }}</div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-filter-content">
                <div class="search-box">
                    <input type="text" id="staffs-search" placeholder="{{ __('Search customers...') }}">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">{{ __('All Activity') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                    <option value="recent">{{ __('Recent Activity') }}</option>
                </select>
                <select class="filter-select" id="roleFilter">
                    <option value="">{{ __('All Customers') }}</option>
                    <option value="verified">{{ __('Verified') }}</option>
                    <option value="unverified">{{ __('Unverified') }}</option>
                </select>
                <button class="filter-btn" onclick="applyFilters()">
                    <i class="fas fa-filter"></i>
                    {{ __('Apply') }}
                </button>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Registration Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Last Activity') }}</th>
                        <th>{{ __('Debt Limit') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody id="users-table-wrapper">
                    @include('user::dashboard.customer.dataTables', [
                        'users' => $users,
                        'store' => $store,
                    ])
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4" id="users-pagination">
            @if ($users->hasPages())
                {{ $users->links() }}
            @endif
        </div>
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
                    {{ __('Are you sure you want to delete customer') }} <strong id="customerNameToDelete"></strong>?
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
                    {{ __('Delete Customer') }}
                </button>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Prepare localized base URLs for navigation
        document.addEventListener('DOMContentLoaded', function() {
            document.body.setAttribute('data-customer-show-base',
                `{{ route('dashboard.customer.show', ['customer' => 'CUSTOMER_ID']) }}`);
            document.body.setAttribute('data-customer-edit-base',
                `{{ route('dashboard.customer.edit', ['customer' => 'CUSTOMER_ID']) }}`);
        });
    </script>
    <style>
        /* Customers Page Specific Styles */
        .users-container {
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
            color: var(--text-primary, #1f2937);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .add-user-btn {
            background: linear-gradient(135deg, #059669, #10b981);
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
            text-decoration: none;
        }

        .add-user-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
            color: white;
        }

        .users-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
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
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-icon.new {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.premium {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin: 0 0 0.5rem 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary, #6b7280);
            margin: 0;
        }

        .search-filter-bar {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .search-filter-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
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
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
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
            color: var(--text-light, #9ca3af);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
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

        .users-table-container {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .users-table th {
            background: var(--bg-secondary, #f9fafb);
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light, #f3f4f6);
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
        }

        .users-table tr:hover {
            background: var(--bg-secondary, #f9fafb);
        }

        .users-table tr:nth-child(even) {
            background: rgba(0, 0, 0, 0.02);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-details h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
        }

        .user-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-secondary, #6b7280);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
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
        }

        .action-btn.edit {
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-secondary, #6b7280);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .action-btn.edit:hover {
            background: #059669;
            color: white;
            border-color: #059669;
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

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .action-btn.view:hover {
            background: #3b82f6;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .search-filter-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .users-stats {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .users-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-actions {
                justify-content: space-between;
            }

            .users-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .users-table-container {
                padding: 1rem;
            }

            .users-table th,
            .users-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        /* Custom Delete Modal Styles */
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            background: #2d2d2d;
            border-radius: 16px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 10000;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px) scale(0.95);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .delete-modal.show .delete-modal-content {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .delete-modal-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid #404040;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-modal-header h3 {
            color: #ffffff;
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .delete-modal-close {
            background: none;
            border: none;
            color: #888888;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            width: 2.5rem;
            height: 2.5rem;
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
            width: 4rem;
            height: 4rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #ef4444;
            font-size: 1.5rem;
        }

        .delete-message {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .delete-message strong {
            color: #ef4444;
        }

        .delete-warning {
            color: #888888;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .delete-modal-footer {
            padding: 1rem 2rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #404040;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #505050;
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

        /* Responsive */
        @media (max-width: 768px) {
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
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('staffs-search');
            let typingTimer;
            const typingDelay = 500; // نصف ثانية تأخير قبل البحث
            const usersTableWrapper = document.getElementById('users-table-wrapper');
            const usersPagination = document.getElementById('users-pagination');

            function fetchUsers(page = 1) {
                const search = searchInput.value;
                const statusFilter = document.getElementById('statusFilter').value;
                const roleFilter = document.getElementById('roleFilter').value;

                // Show loading state
                usersTableWrapper.innerHTML =
                    '<tr><td colspan="7" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><br>جاري التحميل...</td></tr>';

                const params = new URLSearchParams({
                    search: search,
                    status: statusFilter,
                    role: roleFilter,
                    page: page
                });

                fetch(`{{ route('dashboard.customer.index') }}?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        usersTableWrapper.innerHTML = data.html;
                        usersPagination.innerHTML = data.pagination;
                    })
                    .catch(err => {
                        console.error(err);
                        usersTableWrapper.innerHTML =
                            '<tr><td colspan="7" class="text-center py-5 text-danger">حدث خطأ في التحميل</td></tr>';
                    });
            }

            // البحث عند الكتابة
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchUsers();
                }, typingDelay);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            // التعامل مع الباجينيت
            usersPagination.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    e.preventDefault();
                    const url = new URL(e.target.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchUsers(page);
                }
            });
        });

        function applyFilters() {
            const searchInput = document.getElementById('staffs-search');
            const statusFilter = document.getElementById('statusFilter').value;
            const roleFilter = document.getElementById('roleFilter').value;

            // Show loading state
            const tbody = document.getElementById('users-table-wrapper');
            tbody.innerHTML =
                '<tr><td colspan="7" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><br>جاري التحميل...</td></tr>';

            const params = new URLSearchParams({
                search: searchInput.value,
                status: statusFilter,
                role: roleFilter
            });

            fetch(`{{ route('dashboard.customer.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = data.html;
                    document.getElementById('users-pagination').innerHTML = data.pagination;
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="text-center py-5 text-danger">حدث خطأ في التحميل</td></tr>';
                });
        }

        function viewCustomer(customerId) {
            console.log('Viewing customer:', customerId);
            const base = document.body.getAttribute('data-customer-show-base');
            window.location.href = base.replace('CUSTOMER_ID', customerId);
        }

        function editCustomer(customerId) {
            console.log('Editing customer:', customerId);
            const base = document.body.getAttribute('data-customer-edit-base');
            window.location.href = base.replace('CUSTOMER_ID', customerId);
        }


        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            const validationAlert = document.getElementById('validationAlert');

            if (successAlert) {
                successAlert.style.transition = 'opacity 0.3s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            }
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.3s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 300);
            }
            if (validationAlert) {
                validationAlert.style.transition = 'opacity 0.3s ease';
                validationAlert.style.opacity = '0';
                setTimeout(() => validationAlert.remove(), 300);
            }
        }, 5000);

        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }

        // Professional Notifications System
        document.addEventListener('DOMContentLoaded', function() {
            // Show and auto-hide notifications
            setTimeout(() => {
                const notifications = document.querySelectorAll('.notification');
                notifications.forEach(notification => {
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

                    // Animate progress bar
                    const progressBar = notification.querySelector('.notification-progress');
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        progressBar.style.transition = 'width 6000ms linear';
                    }
                });
            }, 100);
        });

        // Delete Modal Functionality
        let currentDeleteForm = null;
        let currentDeleteBtn = null;

        // Handle delete button clicks
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-customer-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-customer-btn');
                const customerName = btn.getAttribute('data-customer-name');
                const customerId = btn.getAttribute('data-customer-id');

                // Set customer name in modal
                document.getElementById('customerNameToDelete').textContent = customerName;

                // Store references for later use
                currentDeleteBtn = btn;

                // Show modal
                const modal = document.getElementById('deleteModal');
                modal.style.display = 'flex';
                modal.classList.add('show');

                // Create form for deletion
                currentDeleteForm = document.createElement('form');
                currentDeleteForm.method = 'POST';
                currentDeleteForm.action = `{{ url('dashboard/customer') }}/${customerId}`;
                currentDeleteForm.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(currentDeleteForm);
            }
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

                // Show loading state
                currentDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Deleting...') }}';
                currentDeleteBtn.disabled = true;

                // Hide modal
                document.getElementById('deleteModal').style.display = 'none';

                // Send AJAX delete request
                fetch(currentDeleteForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-HTTP-Method-Override': 'DELETE'
                        },
                        body: '_method=DELETE&_token={{ csrf_token() }}'
                    })
                    .then(response => {
                        if (response.ok) {
                            // محاولة تحليل JSON
                            return response.json().catch(() => {
                                // إذا فشل تحليل JSON، فهذا يعني نجاح الحذف
                                return {
                                    success: true,
                                    message: '{{ __('Customer deleted successfully') }}'
                                };
                            });
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        // Show success notification
                        const message = data.message || '{{ __('Customer deleted successfully') }}';
                        showSuccessNotification(message);

                        // Remove the row from table
                        const row = document.getElementById(
                            `user-row-${currentDeleteBtn.getAttribute('data-customer-id')}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.8)';
                            setTimeout(() => {
                                row.remove();
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorNotification('{{ __('Error deleting customer') }}');
                        // Restore button state
                        currentDeleteBtn.innerHTML = originalText;
                        currentDeleteBtn.disabled = false;
                    });
            }
        });

        // Professional Success Notification
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification success professional-notification';
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
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
@endpush
