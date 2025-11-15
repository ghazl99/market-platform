@extends('core::dashboard.' . current_store()->type . '.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Edit Order'))

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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

    <div class="order-edit-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Edit Order') }} #{{ $order->id }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.order.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>

        <div class="edit-form-container">
            <form id="order-edit-form" action="{{ route('dashboard.order.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Order Information') }}</h3>
                        <span class="order-status {{ $order->status }}">
                            @switch($order->status)
                                @case('completed')
                                    {{ __('Completed') }}
                                @break

                                @case('pending')
                                    {{ __('Automatic Processing') }}
                                @break

                                @case('processing')
                                    {{ __('Manual Processing') }}
                                @break

                                @case('canceled')
                                @case('cancelled')
                                    {{ __('Cancelled') }}
                                @break

                                @default
                                    {{ __('Status') }}: {{ ucfirst($order->status) }}
                            @endswitch
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">{{ __('Order Status') }}</label>
                        <select name="status" id="status" class="form-select">
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                {{ __('Manual Processing') }}</option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                {{ __('Automatic Processing') }}</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                {{ __('Completed') }}</option>
                            <option value="canceled"
                                {{ $order->status === 'canceled' || $order->status === 'cancelled' ? 'selected' : '' }}>
                                {{ __('Cancelled') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="customer_name" class="form-label">{{ __('Customer Name') }}</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-input"
                            value="{{ $order->user->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="customer_email" class="form-label">{{ __('Customer Email') }}</label>
                        <input type="email" name="customer_email" id="customer_email" class="form-input"
                            value="{{ $order->user->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="total_amount" class="form-label">{{ __('Total Amount') }}</label>
                        <input type="number" name="total_amount" id="total_amount" class="form-input"
                            value="{{ $order->total_amount }}" step="0.01" readonly>
                    </div>

                    <div class="form-group">
                        <label for="payment_method" class="form-label">{{ __('Payment Method') }}</label>
                        <input type="text" name="payment_method" id="payment_method" class="form-input"
                            value="{{ __('Electronic Wallet') }}" readonly>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn primary">
                        <i class="fas fa-save"></i>
                        {{ __('Save Changes') }}
                    </button>
                    <a href="{{ route('dashboard.order.show', $order->id) }}" class="action-btn secondary">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

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
            background: #2d2d2d;
            border: 1px solid #404040;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification.hide {
            opacity: 0;
            transform: translateX(100%);
        }

        .notification.success {
            border-left: 4px solid #10b981;
        }

        .notification.error {
            border-left: 4px solid #ef4444;
        }

        .notification-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .notification.success .notification-icon {
            color: #10b981;
        }

        .notification.error .notification-icon {
            color: #ef4444;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .notification-message {
            font-size: 13px;
            color: #d1d5db;
            line-height: 1.4;
        }

        .notification-close {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-close:hover {
            color: #ffffff;
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 0 0 8px 8px;
            width: 0%;
        }

        .notification.error .notification-progress {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        /* Order Edit Page Specific Styles */
        .order-edit-container {
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
            color: #ffffff;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .back-btn {
            background: #2d2d2d;
            color: #cccccc;
            border: 1px solid #404040;
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

        .back-btn:hover {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .edit-form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #404040;
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333333;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .order-status.completed {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .order-status.pending {
            background: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .order-status.processing {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .order-status.canceled {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            color: #cccccc;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #1a1a1a;
            border: 1px solid #404040;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-input[readonly] {
            background: #333333;
            color: #999999;
            cursor: not-allowed;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            min-width: 180px;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn i {
            font-size: 1.1rem;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: 2px solid #059669;
        }

        .action-btn.primary:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        .action-btn.secondary {
            background: #2d2d2d;
            color: #ffffff;
            border: 2px solid #404040;
        }

        .action-btn.secondary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .order-edit-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-actions {
                justify-content: space-between;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: center;
            }

            .action-btn {
                min-width: 100%;
                max-width: 300px;
            }
        }

        /* Light Mode Styles - Maximum Priority */
        html[data-theme="light"] .order-edit-container,
        html[data-theme="light"] body .order-edit-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .back-btn,
        html[data-theme="light"] body .back-btn {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .back-btn:hover,
        html[data-theme="light"] body .back-btn:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-card,
        html[data-theme="light"] body .form-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .card-header,
        html[data-theme="light"] body .card-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .card-title,
        html[data-theme="light"] body .card-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input,
        html[data-theme="light"] .form-select,
        html[data-theme="light"] body .form-input,
        html[data-theme="light"] body .form-select {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input:focus,
        html[data-theme="light"] .form-select:focus,
        html[data-theme="light"] body .form-input:focus,
        html[data-theme="light"] body .form-select:focus {
            background: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-input[readonly],
        html[data-theme="light"] body .form-input[readonly] {
            background: #f9fafb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .form-select {
            background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 0.5rem center !important;
            background-size: 1.5em 1.5em !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
        }

        [dir="rtl"] html[data-theme="light"] .form-select,
        [dir="rtl"] html[data-theme="light"] body .form-select {
            background-position: left 0.5rem center !important;
            padding-right: 1rem !important;
            padding-left: 2.5rem !important;
        }

        html[data-theme="light"] .action-btn.secondary,
        html[data-theme="light"] body .action-btn.secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 2px solid #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.secondary:hover,
        html[data-theme="light"] body .action-btn.secondary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            border-color: #059669 !important;
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
            color: #374151 !important;
        }
    </style>
@endsection
@push('scripts')
    <script>
        // Ensure theme is applied
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                // Force reflow to apply styles
                document.body.offsetHeight;
                // Re-apply theme to ensure styles are loaded
                document.documentElement.setAttribute('data-theme', 'light');
            }
        });

        // Show and auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
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
            });

            // Form submission
            document.getElementById('order-edit-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                // Show loading state on submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Saving...') }}';
                submitBtn.disabled = true;

                console.log('Form submitted:', form.action);
                console.log('Form data:', Object.fromEntries(formData));

                // Check CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF token element:', csrfToken);
                console.log('CSRF token value:', csrfToken ? csrfToken.getAttribute('content') :
                    'NOT FOUND');

                // Add timeout to prevent infinite loading
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 seconds timeout

                const fetchOptions = {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData,
                    signal: controller.signal
                };

                console.log('Fetch options:', fetchOptions);
                console.log('Starting fetch request...');

                fetch(form.action, fetchOptions)
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        console.log('Response ok:', response.ok);

                        if (!response.ok) {
                            // Try to get error message from response
                            return response.text().then(text => {
                                console.error('Error response text:', text);
                                throw new Error(
                                    `HTTP error! status: ${response.status}, message: ${text}`
                                );
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        if (data.success) {
                            // Restore button state immediately
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;

                            // Redirect immediately to orders index with success message
                            const successMessage = encodeURIComponent(data.message);
                            window.location.href = '{{ route('dashboard.order.index') }}?success=' +
                                successMessage;
                        } else {
                            showOrderNotification('error', data.message || 'حدث خطأ غير متوقع');

                            // Restore button state on error
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        console.error('Error name:', error.name);
                        console.error('Error message:', error.message);
                        console.error('Error stack:', error.stack);

                        // Clear timeout
                        clearTimeout(timeoutId);

                        let errorMessage = 'حدث خطأ أثناء حفظ التغييرات';

                        if (error.name === 'AbortError') {
                            errorMessage = 'انتهت مهلة الطلب. يرجى المحاولة مرة أخرى.';
                        } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                            errorMessage = 'خطأ في الاتصال بالخادم. تحقق من اتصال الإنترنت.';
                        } else if (error.message) {
                            errorMessage += ': ' + error.message;
                        }

                        showOrderNotification('error', errorMessage);

                        // Restore button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        });

        function showOrderNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-${type === 'success' ? 'check' : 'times'}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${type === 'success' ? 'نجح' : 'خطأ'}</div>
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
