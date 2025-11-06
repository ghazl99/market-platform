@extends('core::dashboard.layouts.app')

@section('title', __('طلبات الدفع'))

@push('styles')
    <style>
        .amount-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .original-amount {
            font-weight: bold;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .usd-amount {
            font-size: 0.9em;
            color: #cbd5e1;
            transition: color 0.3s ease;
        }

        .exchange-rate {
            font-size: 0.8em;
            color: #9ca3af;
            font-style: italic;
            transition: color 0.3s ease;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-name {
            font-weight: 600;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .user-email {
            font-size: 0.9em;
            color: #cbd5e1;
            transition: color 0.3s ease;
        }

        .payment-method {
            font-weight: 500;
            color: #cbd5e1;
            transition: color 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <!-- Notifications -->
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
        <h1 class="page-title">{{ __('طلبات الدفع') }}</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="payment-stats">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">{{ $paymentRequests->count() }}</div>
            <div class="stat-label">{{ __('إجمالي الطلبات') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $paymentRequests->where('status', 'pending')->count() }}</div>
            <div class="stat-label">{{ __('في الانتظار') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $paymentRequests->where('status', 'approved')->count() }}</div>
            <div class="stat-label">{{ __('مقبولة') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-number">{{ $paymentRequests->where('status', 'rejected')->count() }}</div>
            <div class="stat-label">{{ __('مرفوضة') }}</div>
        </div>
    </div>

    <!-- Payment Requests Table -->
    <div class="payment-table-container">
        <div class="table-header">
            <h3>{{ __('قائمة طلبات الدفع') }}</h3>
            <div class="table-actions">
                <input type="text" id="payment-search" placeholder="{{ __('البحث في طلبات الدفع...') }}"
                    class="search-input">
            </div>
        </div>

        <div class="table-responsive">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>{{ __('المستخدم') }}</th>
                        <th>{{ __('المبلغ') }}</th>
                        <th>{{ __('طريقة الدفع') }}</th>
                        <th>{{ __('الحالة') }}</th>
                        <th>{{ __('تاريخ الطلب') }}</th>
                        <th>{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody id="payment-table-body">
                    @include('wallet::dashboard.payment-requests.dataTables')
                </tbody>
            </table>
        </div>

        <div id="payment-pagination" class="pagination-container">
            {{ $paymentRequests->links() }}
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">{{ __('تأكيد العملية') }}</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i>
                            {{ __('ملاحظات (اختياري)') }}
                        </label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"
                            placeholder="{{ __('أضف ملاحظات حول هذا القرار...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        {{ __('تأكيد') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Professional Notification Styles */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .notification {
            background: #374151;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            position: relative;
            overflow: hidden;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
            border-left: 4px solid #10b981;
        }

        .notification.error {
            border-left: 4px solid #ef4444;
        }

        .notification.warning {
            border-left: 4px solid #f59e0b;
        }

        .notification.info {
            border-left: 4px solid #3b82f6;
        }

        .notification-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .notification.success .notification-icon {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .notification.error .notification-icon {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .notification.warning .notification-icon {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .notification.info .notification-icon {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .notification-content {
            padding-right: 3rem;
        }

        .notification-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .notification.success .notification-title {
            color: #10b981;
        }

        .notification.error .notification-title {
            color: #ef4444;
        }

        .notification.warning .notification-title {
            color: #f59e0b;
        }

        .notification.info .notification-title {
            color: #3b82f6;
        }

        .notification-message {
            font-size: 14px;
            color: #d1d5db;
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
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            width: 0%;
            transition: width 6s linear;
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
            transform: translateX(-100%);
        }

        [dir="rtl"] .notification.show {
            transform: translateX(0);
        }

        [dir="rtl"] .notification.hide {
            transform: translateX(-100%);
        }

        [dir="rtl"] .notification-icon {
            right: auto;
            left: 1rem;
        }

        [dir="rtl"] .notification-content {
            padding-right: 0;
            padding-left: 3rem;
        }

        [dir="rtl"] .notification-close {
            right: auto;
            left: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notification-container {
                right: 10px;
                left: 10px;
                max-width: none;
            }

            [dir="rtl"] .notification-container {
                right: 10px;
                left: 10px;
            }

            .notification-title {
                font-size: 15px;
            }

            .notification-message {
                font-size: 13px;
            }
        }

        /* Payment Requests Styles - Dark Theme */
        .payment-container {
            padding: 2rem;
            background: #111827;
            min-height: 100vh;
            transition: background-color 0.3s ease;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #334155;
            transition: border-color 0.3s ease;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            transition: color 0.3s ease;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
        }

        .payment-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            background: #334155;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-icon.approved {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
        }

        .stat-icon.rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #d1d5db;
            font-weight: 500;
        }

        .payment-table-container {
            background: #1e293b;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #334155;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
            background: #1f2937;
            transition: all 0.3s ease;
        }

        .table-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            transition: color 0.3s ease;
        }

        .table-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.75rem 1rem;
            border: 1px solid #475569;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 250px;
            background: #334155;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: #475569;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-table th {
            background: #1f2937;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            color: #ffffff;
            border-bottom: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .payment-table td {
            padding: 1rem;
            border-bottom: 1px solid #334155;
            vertical-align: middle;
            color: #ffffff;
            background: #1e293b;
            transition: all 0.3s ease;
        }

        .payment-table tbody tr {
            background: #1e293b;
        }

        .payment-table tbody tr:hover {
            background: #334155;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .action-btn {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            min-width: 120px;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .action-btn i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .action-btn:hover i {
            transform: scale(1.1);
        }

        .action-btn.approve {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .action-btn.approve:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .action-btn.reject {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn.reject:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .action-btn.view {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .action-btn.view:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .btn-text {
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-name {
            font-weight: 500;
            color: #ffffff;
        }

        .user-email {
            color: #d1d5db;
            font-size: 0.9rem;
        }

        .amount-text {
            font-weight: 600;
            color: #10b981;
            font-size: 1.1rem;
        }

        .payment-method {
            color: #d1d5db;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-badge.approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .date-text {
            color: #d1d5db;
            font-size: 0.9rem;
        }


        .pagination-container {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            border-top: 1px solid #334155;
            background: #1f2937;
            transition: all 0.3s ease;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: #1e293b;
            margin: 5% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border: 1px solid #334155;
            animation: modalSlideIn 0.3s ease-out;
            transition: all 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s ease;
        }

        .modal-header h3 {
            margin: 0;
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #9ca3af;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #334155;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            transition: border-color 0.3s ease;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
        }

        .form-label i {
            color: #d1d5db;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #475569;
            border-radius: 8px;
            font-size: 0.9rem;
            background: #334155;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: #475569;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        }

        /* Pagination Styles for Light Mode */
        html[data-theme="light"] .pagination .page-link,
        html[data-theme="light"] body .pagination .page-link {
            color: #374151 !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .pagination .page-link:hover,
        html[data-theme="light"] body .pagination .page-link:hover {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
        }

        html[data-theme="light"] .pagination .page-item.active .page-link,
        html[data-theme="light"] body .pagination .page-item.active .page-link {
            background-color: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .payment-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .search-input {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }

        /* Light Mode Styles - Maximum Priority */
        html[data-theme="light"] .payment-container,
        html[data-theme="light"] body .payment-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .page-header,
        html[data-theme="light"] body .page-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .stat-card,
        html[data-theme="light"] body .stat-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .stat-card:hover,
        html[data-theme="light"] body .stat-card:hover {
            background: #f9fafb !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        html[data-theme="light"] .stat-number,
        html[data-theme="light"] body .stat-number {
            color: #111827 !important;
        }

        html[data-theme="light"] .stat-label,
        html[data-theme="light"] body .stat-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .payment-table-container,
        html[data-theme="light"] body .payment-table-container {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .table-header,
        html[data-theme="light"] body .table-header {
            background: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .table-header h3,
        html[data-theme="light"] body .table-header h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .search-input,
        html[data-theme="light"] body .search-input {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .search-input:focus,
        html[data-theme="light"] body .search-input:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .payment-table th,
        html[data-theme="light"] body .payment-table th {
            background: #f9fafb !important;
            color: #111827 !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .payment-table td,
        html[data-theme="light"] body .payment-table td {
            background: #ffffff !important;
            color: #374151 !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .payment-table tbody tr,
        html[data-theme="light"] body .payment-table tbody tr {
            background: #ffffff !important;
        }

        html[data-theme="light"] .payment-table tbody tr:hover,
        html[data-theme="light"] body .payment-table tbody tr:hover {
            background: #f9fafb !important;
        }

        html[data-theme="light"] .original-amount,
        html[data-theme="light"] body .original-amount {
            color: #111827 !important;
        }

        html[data-theme="light"] .usd-amount,
        html[data-theme="light"] body .usd-amount {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .user-name,
        html[data-theme="light"] body .user-name {
            color: #111827 !important;
        }

        html[data-theme="light"] .user-email,
        html[data-theme="light"] body .user-email {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .payment-method,
        html[data-theme="light"] body .payment-method {
            color: #374151 !important;
        }

        html[data-theme="light"] .date-text,
        html[data-theme="light"] body .date-text {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .pagination-container,
        html[data-theme="light"] body .pagination-container {
            background: #ffffff !important;
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .modal-content,
        html[data-theme="light"] body .modal-content {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .modal-header,
        html[data-theme="light"] body .modal-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .modal-header h3,
        html[data-theme="light"] body .modal-header h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .modal-footer,
        html[data-theme="light"] body .modal-footer {
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-control,
        html[data-theme="light"] body .form-control {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-control:focus,
        html[data-theme="light"] body .form-control:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .btn-secondary,
        html[data-theme="light"] body .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .btn-secondary:hover,
        html[data-theme="light"] body .btn-secondary:hover {
            background: #e5e7eb !important;
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
            background: rgba(0, 0, 0, 0.05) !important;
            color: #374151 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Professional Notification System
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure theme is applied
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                // Force reflow to apply styles
                document.body.offsetHeight;
                // Re-apply theme to ensure styles are loaded
                document.documentElement.setAttribute('data-theme', 'light');
            }

            setTimeout(() => {
                const notifications = document.querySelectorAll('.professional-notification');
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

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('payment-search');
            let typingTimer;
            const typingDelay = 500;

            function fetchPaymentRequests(page = 1) {
                const search = searchInput.value;

                fetch(`{{ route('dashboard.payment-requests.index') }}?search=${encodeURIComponent(search)}&page=${page}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('payment-table-body').innerHTML = data.html;
                        document.getElementById('payment-pagination').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchPaymentRequests();
                }, typingDelay);
            });

            // Pagination click handler
            document.addEventListener('click', function(e) {
                if (e.target.matches('.pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.href);
                    const page = url.searchParams.get('page');
                    fetchPaymentRequests(page);
                }
            });
        });

        // Modal functions
        function openApprovalModal(paymentId, action) {
            const modal = document.getElementById('approvalModal');
            const form = document.getElementById('approvalForm');
            const title = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');

            form.action = `{{ url('ar/dashboard/payment-requests') }}/${paymentId}/update`;

            if (action === 'approve') {
                title.textContent = '{{ __('تأكيد الموافقة') }}';
                submitBtn.textContent = '{{ __('موافقة') }}';
                submitBtn.className = 'btn btn-primary';
                submitBtn.innerHTML = '<i class="fas fa-check"></i> {{ __('موافقة') }}';

                // Add hidden input for status
                let statusInput = form.querySelector('input[name="status"]');
                if (!statusInput) {
                    statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    form.appendChild(statusInput);
                }
                statusInput.value = 'approved';
            } else {
                title.textContent = '{{ __('تأكيد الرفض') }}';
                submitBtn.textContent = '{{ __('رفض') }}';
                submitBtn.className = 'btn btn-primary';
                submitBtn.innerHTML = '<i class="fas fa-times"></i> {{ __('رفض') }}';

                // Add hidden input for status
                let statusInput = form.querySelector('input[name="status"]');
                if (!statusInput) {
                    statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    form.appendChild(statusInput);
                }
                statusInput.value = 'rejected';
            }

            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('approvalModal');
            modal.style.display = 'none';

            // Clear form
            document.getElementById('notes').value = '';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('approvalModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
@endpush
