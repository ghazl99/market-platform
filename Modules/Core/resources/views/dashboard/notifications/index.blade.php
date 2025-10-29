@extends('core::dashboard.layouts.app')
@section('title', __('الإشعارات'))

@push('styles')
    <style>
        /* Dashboard Notifications Styles - Theme Support */
        .notifications-page {
            padding: 2rem 0;
            background: var(--bg-secondary);
            min-height: calc(100vh - 80px);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .notifications-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-header {
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.3s ease;
        }

        .page-title i {
            color: var(--primary-color);
            transition: color 0.3s ease;
        }

        .notifications-list {
            background: var(--bg-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: var(--bg-secondary);
        }

        .notification-item.unread {
            background: var(--bg-primary);
            border-left: 4px solid var(--primary-color);
            position: relative;
        }

        .notification-item.unread::before {
            content: '';
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            box-shadow: 0 0 0 2px var(--bg-primary);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .notification-icon.default {
            background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
            color: var(--text-secondary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notification-icon.order {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            color: #dbeafe;
            box-shadow: 0 2px 4px rgba(29, 78, 216, 0.3);
        }

        .notification-icon.payment {
            background: linear-gradient(135deg, #166534 0%, #16a34a 100%);
            color: #dcfce7;
            box-shadow: 0 2px 4px rgba(22, 101, 52, 0.3);
        }

        .notification-icon.user {
            background: linear-gradient(135deg, #92400e 0%, #d97706 100%);
            color: #fef3c7;
            box-shadow: 0 2px 4px rgba(146, 64, 14, 0.3);
        }

        .notification-icon.test {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #d1fae5;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
            transition: color 0.3s ease;
        }

        .notification-message {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin: 0 0 0.5rem 0;
            transition: color 0.3s ease;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .notification-time {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-notification {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid;
            cursor: pointer;
        }

        .btn-delete {
            background: var(--error-color);
            color: #ffffff;
            border-color: var(--error-color);
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: #ffffff;
            transform: scale(1.05);
        }

        .btn-delete:active {
            transform: scale(0.95);
        }

        .btn-delete:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--text-lighter);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            background: var(--bg-primary);
            transition: all 0.3s ease;
        }

        .pagination .page-link {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
        }

        .pagination .page-item.disabled .page-link {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-lighter);
        }

        /* Light Mode Specific Styles */
        [data-theme="light"] .notifications-page {
            background: #ffffff;
        }

        [data-theme="light"] .page-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .notifications-list {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .notification-item {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }

        [data-theme="light"] .notification-item:hover {
            background: #f9fafb;
        }

        [data-theme="light"] .notification-item.unread {
            background: #f0fdf4;
            border-left: 4px solid var(--primary-color);
        }

        [data-theme="light"] .notification-item.unread::before {
            box-shadow: 0 0 0 2px #ffffff;
        }

        [data-theme="light"] .notification-title {
            color: #111827;
        }

        [data-theme="light"] .notification-message {
            color: #374151;
        }

        [data-theme="light"] .notification-meta {
            color: #6b7280;
        }

        [data-theme="light"] .notification-time {
            color: #6b7280;
        }

        [data-theme="light"] .empty-state {
            color: #6b7280;
        }

        [data-theme="light"] .empty-state h3 {
            color: #111827;
        }

        [data-theme="light"] .empty-state p {
            color: #374151;
        }

        [data-theme="light"] .pagination-wrapper {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
        }

        [data-theme="light"] .notification-icon.default {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #6b7280;
        }

        /* Dark Mode Specific Styles */
        [data-theme="dark"] .notifications-page {
            background: #111827;
        }

        [data-theme="dark"] .page-header {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .notifications-list {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .notification-item {
            background: #1f2937;
            border-bottom: 1px solid #374151;
            color: #f9fafb;
        }

        [data-theme="dark"] .notification-item:hover {
            background: #374151;
        }

        [data-theme="dark"] .notification-item.unread {
            background: #1f2937;
            border-left: 4px solid #10b981;
        }

        [data-theme="dark"] .notification-item.unread::before {
            box-shadow: 0 0 0 2px #1f2937;
        }

        [data-theme="dark"] .notification-title {
            color: #f9fafb;
        }

        [data-theme="dark"] .notification-message {
            color: #d1d5db;
        }

        [data-theme="dark"] .notification-meta {
            color: #9ca3af;
        }

        [data-theme="dark"] .notification-time {
            color: #9ca3af;
        }

        [data-theme="dark"] .empty-state {
            color: #9ca3af;
        }

        [data-theme="dark"] .empty-state h3 {
            color: #f9fafb;
        }

        [data-theme="dark"] .empty-state p {
            color: #d1d5db;
        }

        [data-theme="dark"] .pagination-wrapper {
            background: #1f2937;
            border-top: 1px solid #374151;
        }

        [data-theme="dark"] .notification-icon.default {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            color: #d1d5db;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notifications-container {
                padding: 0 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .notification-item {
                padding: 1rem;
            }

            .notification-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-left: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="notifications-page">
        <div class="notifications-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-bell"></i>
                    {{ __('الإشعارات') }}
                </h1>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                @php
                    $locale = app()->getLocale();
                @endphp

                @forelse($notifications as $notification)
                    @php
                        $data = is_array($notification->data)
                            ? $notification->data
                            : json_decode($notification->data, true);
                        $title = $data['title'][$locale] ?? ($data['title']['en'] ?? 'إشعار');
                        $message = $data['message'][$locale] ?? ($data['message']['en'] ?? '');
                        $url = $data['url'] ?? '#';

                        // إصلاح الإشعارات القديمة التي تحتوي على "مستخدم غير معروف"
                        if (isset($data['order_id']) && strpos($message, 'مستخدم غير معروف') !== false) {
                            try {
                                $order = \Modules\Order\Models\Order::with('user')->find($data['order_id']);
                                if ($order && $order->user) {
                                    $message = str_replace('مستخدم غير معروف', $order->user->name, $message);
                                }
                            } catch (\Exception $e) {
                                // Ignore errors
                            }
                        }

                        // إصلاح نصوص "عميل غير معروف" أيضاً
                        if (isset($data['order_id']) && strpos($message, 'عميل غير معروف') !== false) {
                            try {
                                $order = \Modules\Order\Models\Order::with('user')->find($data['order_id']);
                                if ($order && $order->user) {
                                    $message = str_replace('عميل غير معروف', $order->user->name, $message);
                                }
                            } catch (\Exception $e) {
                                // Ignore errors
                            }
                        }

                        // تحديد نوع الإشعار للأيقونة
                        $type = $data['type'] ?? 'default';
                        $iconClass = match ($type) {
                            'new_order', 'order_confirmed' => 'fas fa-shopping-cart order',
                            'payment_request', 'payment_approved', 'payment_rejected' => 'fas fa-wallet payment',
                            'user_registered' => 'fas fa-user user',
                            'test' => 'fas fa-flask test',
                            default => 'fas fa-bell default',
                        };
                    @endphp

                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">

                        <div class="notification-icon {{ explode(' ', $iconClass)[1] }}">
                            <i class="{{ $iconClass }}"></i>
                        </div>

                        <div class="notification-content">
                            <h3 class="notification-title">{{ $title }}</h3>
                            <p class="notification-message">{{ $message }}</p>

                            <div class="notification-meta">
                                <span class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="notification-actions">
                                <button type="button" class="btn-notification btn-delete"
                                    data-notification-id="{{ $notification->id }}">
                                    <i class="fas fa-trash"></i>
                                    {{ __('حذف') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h3>{{ __('لا توجد إشعارات') }}</h3>
                        <p>{{ __('لم تتلق أي إشعارات بعد.') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="pagination-wrapper">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('تأكيد الحذف') }}</h3>
                <button type="button" class="modal-close" onclick="hideDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>{{ __('هل أنت متأكد من حذف هذا الإشعار؟') }}</p>
                <p class="text-muted">{{ __('لا يمكن التراجع عن هذا الإجراء.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="hideDeleteModal()">
                    {{ __('إلغاء') }}
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-confirm">
                        {{ __('حذف') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none !important;
            align-items: center;
            justify-content: center;
            visibility: hidden !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        /* Force show modal when needed */
        .modal-overlay.show {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
        }

        /* Ensure modal is properly positioned */
        .modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
        }

        /* Debug styles */
        .btn-delete {
            position: relative;
        }

        .btn-delete:active {
            transform: scale(0.95);
        }

        /* Force hide modal by default */
        #deleteModal {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        #deleteModal.show {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        .modal-content {
            background: var(--bg-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            max-width: 400px;
            width: 90%;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: var(--text-primary);
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 1.5rem;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }

        .modal-body p {
            margin: 0 0 0.5rem 0;
        }

        .text-muted {
            color: var(--text-light);
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--bg-tertiary);
            border-color: var(--border-light);
        }

        .btn-confirm {
            background: var(--error-color);
            color: #ffffff;
            border: 1px solid var(--error-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        /* Light Mode Modal */
        [data-theme="light"] .modal-content {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .modal-header {
            border-bottom: 1px solid #e5e7eb;
        }

        [data-theme="light"] .modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        [data-theme="light"] .modal-footer {
            border-top: 1px solid #e5e7eb;
        }

        [data-theme="light"] .btn-cancel:hover {
            background: #f3f4f6;
        }

        /* Dark Mode Modal */
        [data-theme="dark"] .modal-content {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .modal-header {
            border-bottom: 1px solid #374151;
        }

        [data-theme="dark"] .modal-close:hover {
            background: #374151;
            color: #f9fafb;
        }

        [data-theme="dark"] .modal-footer {
            border-top: 1px solid #374151;
        }

        [data-theme="dark"] .btn-cancel:hover {
            background: #4b5563;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Delete Modal Functions - Global scope
        function showDeleteModal(notificationId) {
            console.log('Showing delete modal for notification:', notificationId);
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            if (modal && form) {
                form.action = `/dashboard/notifications/${notificationId}`;
                modal.classList.add('show');
                console.log('Modal shown successfully');

                // Add a small delay to ensure CSS is applied
                setTimeout(function() {
                    if (modal.classList.contains('show')) {
                        console.log('Modal is visible');
                    } else {
                        console.log('Modal is not visible, trying again...');
                        modal.classList.add('show');
                    }
                }, 100);
            } else {
                console.error('Modal or form not found');
                console.log('Modal element:', modal);
                console.log('Form element:', form);
            }
        }

        function hideDeleteModal() {
            console.log('Hiding delete modal');
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.remove('show');
                console.log('Modal hidden successfully');
            }
        }

        // Make functions globally available
        window.showDeleteModal = showDeleteModal;
        window.hideDeleteModal = hideDeleteModal;

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing notification system...');
            initializeNotificationSystem();
        });

        // Initialize notification system
        function initializeNotificationSystem() {
            // Setup delete buttons with event listeners
            const deleteButtons = document.querySelectorAll('.btn-delete');
            console.log('Found delete buttons:', deleteButtons.length);

            deleteButtons.forEach(function(button) {
                // Remove any existing event listeners
                button.removeEventListener('click', handleDeleteClick);

                // Add new event listener
                button.addEventListener('click', handleDeleteClick);

                // Add visual feedback
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Setup modal
            const modal = document.getElementById('deleteModal');
            if (modal) {
                console.log('Modal found, setting up event listeners');

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideDeleteModal();
                    }
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modal.classList.contains('show')) {
                        hideDeleteModal();
                    }
                });

                console.log('Modal event listeners set up successfully');
            } else {
                console.error('Modal not found during initialization');
            }
        }

        // Handle delete button click
        function handleDeleteClick(e) {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = this.getAttribute('data-notification-id');
            console.log('Delete button clicked for notification:', notificationId);
            showDeleteModal(notificationId);
        }

        // Also try to initialize immediately if DOM is already loaded
        if (document.readyState !== 'loading') {
            console.log('DOM already loaded, running initialization immediately...');
            initializeNotificationSystem();
        }

        // Auto refresh notifications every 30 seconds
        setInterval(function() {
            // يمكن إضافة AJAX call لتحديث الإشعارات تلقائياً
        }, 30000);

        // Debug function to test everything
        function debugNotificationSystem() {
            console.log('=== DEBUG NOTIFICATION SYSTEM ===');
            console.log('Delete buttons found:', document.querySelectorAll('.btn-delete').length);
            console.log('Modal found:', !!document.getElementById('deleteModal'));
            console.log('Form found:', !!document.getElementById('deleteForm'));
            console.log('showDeleteModal function:', typeof window.showDeleteModal);
            console.log('hideDeleteModal function:', typeof window.hideDeleteModal);
            console.log('================================');
        }

        // Make debug function available globally
        window.debugNotificationSystem = debugNotificationSystem;

        // Run debug after a short delay
        setTimeout(debugNotificationSystem, 1000);

        // Test delete functionality
        function testDeleteFunction() {
            console.log('Testing delete functionality...');
            const deleteButtons = document.querySelectorAll('.btn-delete');
            if (deleteButtons.length > 0) {
                console.log('Found delete buttons, testing first one...');
                const firstButton = deleteButtons[0];
                const notificationId = firstButton.getAttribute('data-notification-id');
                console.log('Testing with notification ID:', notificationId);
                showDeleteModal(notificationId);
            } else {
                console.log('No delete buttons found');
            }
        }

        // Make test function available globally
        window.testDeleteFunction = testDeleteFunction;
    </script>
@endpush
