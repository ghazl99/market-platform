@extends('core::dashboard.layouts.app')

@section('title', __('Payment Methods'))

@push('styles')
    <style>
        .payment-methods-container {
            padding: 2rem;
            background: var(--bg-primary);
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .add-button {
            background: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-button:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .payment-method-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            text-align: center;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .payment-method-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transform: translateY(-4px);
            border-color: var(--primary-color);
        }

        .payment-method-card:hover .delete-button {
            opacity: 1;
        }

        .delete-button {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: #ef4444;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            opacity: 0;
            z-index: 10;
        }

        .delete-button:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        /* Delete Confirmation Modal */
        .delete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .delete-modal.show {
            display: flex;
        }

        .delete-modal-content {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .delete-modal.show .delete-modal-content {
            transform: scale(1);
        }

        .delete-modal-icon {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .delete-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .delete-modal-message {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .delete-modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .delete-modal-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
        }

        .delete-modal-btn-cancel {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .delete-modal-btn-cancel:hover {
            background: var(--bg-primary);
            transform: translateY(-2px);
        }

        .delete-modal-btn-confirm {
            background: #ef4444;
            color: white;
        }

        .delete-modal-btn-confirm:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Professional Notifications */
        .professional-notification {
            position: fixed;
            top: 20px;
            left: 20px;
            min-width: 350px;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
            z-index: 9999;
            transform: translateX(-100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid;
            overflow: hidden;
        }

        .professional-notification:hover {
            transform: translateX(5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.15);
        }

        .professional-notification.show:hover {
            transform: translateX(5px);
        }

        .professional-notification.hide {
            transform: translateX(-100%);
            opacity: 0;
        }

        .professional-notification.success {
            border-left-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        }

        .professional-notification.error {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        .professional-notification.warning {
            border-left-color: #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
        }

        .professional-notification.info {
            border-left-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        }

        .notification-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .notification.success .notification-icon {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .notification.error .notification-icon {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .notification.warning .notification-icon {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .notification.info .notification-icon {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .notification-content {
            padding: 20px 20px 20px 80px;
            position: relative;
        }

        .notification-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .notification-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
        }

        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
            animation: progress 6s linear forwards;
        }

        .notification.success .notification-progress {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.8), rgba(16, 185, 129, 0.3));
        }

        .notification.error .notification-progress {
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.8), rgba(239, 68, 68, 0.3));
        }

        .notification.warning .notification-progress {
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.8), rgba(245, 158, 11, 0.3));
        }

        .notification.info .notification-progress {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.8), rgba(59, 130, 246, 0.3));
        }

        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .payment-method-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
        }

        .payment-method-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .payment-method-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            text-align: center;
        }

        .payment-method-status {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid var(--bg-secondary);
        }

        .status-active {
            background: #10b981;
        }

        .status-inactive {
            background: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .payment-methods-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .payment-methods-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .payment-methods-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .payment-methods-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .payment-methods-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Light Mode Styles */
        html[data-theme="light"] .payment-methods-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .page-header {
            border-bottom: 2px solid #e5e7eb !important;
        }

        html[data-theme="light"] .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .payment-method-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .payment-method-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        html[data-theme="light"] .payment-method-icon {
            background: #f9fafb !important;
            border: 2px solid #e5e7eb !important;
        }

        html[data-theme="light"] .payment-method-name {
            color: #111827 !important;
        }

        html[data-theme="light"] .empty-state {
            color: #374151 !important;
        }

        html[data-theme="light"] .empty-state i {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .empty-state h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-content {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .delete-modal-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-message {
            color: #374151 !important;
        }

        html[data-theme="light"] .delete-modal-btn-cancel {
            background: #ffffff !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        /* Dark Mode Styles */
        html[data-theme="dark"] .payment-methods-container {
            background: #1a1a1a !important;
        }

        html[data-theme="dark"] .page-header {
            border-bottom: 2px solid #374151 !important;
        }

        html[data-theme="dark"] .page-title {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .payment-method-card {
            background: #1f2937 !important;
            border: 1px solid #374151 !important;
        }

        html[data-theme="dark"] .payment-method-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4) !important;
        }

        html[data-theme="dark"] .payment-method-icon {
            background: #111827 !important;
            border: 2px solid #374151 !important;
        }

        html[data-theme="dark"] .payment-method-name {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .empty-state {
            color: #d1d5db !important;
        }

        html[data-theme="dark"] .empty-state i {
            color: #888888 !important;
        }

        html[data-theme="dark"] .empty-state h3 {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .delete-modal-content {
            background: #1f2937 !important;
            border: 1px solid #374151 !important;
        }

        html[data-theme="dark"] .delete-modal-title {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .delete-modal-message {
            color: #d1d5db !important;
        }

        html[data-theme="dark"] .delete-modal-btn-cancel {
            background: #1f2937 !important;
            color: #d1d5db !important;
            border: 1px solid #374151 !important;
        }
    </style>
@endpush

@section('content')
    <div class="payment-methods-container">
        <!-- Notifications -->
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
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Payment Methods') }}</h1>
            <a href="{{ route('dashboard.dashboard.payment-methods.create') }}" class="add-button">
                <i class="fas fa-plus"></i>
                {{ __('Add Payment Method') }}
            </a>
        </div>

        @if ($paymentMethods->count() > 0)
            <!-- Payment Methods Grid -->
            <div class="payment-methods-grid">
                @foreach ($paymentMethods as $method)
                    <div class="payment-method-card">
                        <!-- Delete Button -->
                        <button class="delete-button" onclick="deletePaymentMethod({{ $method->id }})"
                            title="{{ __('Delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Status Indicator -->
                        <div class="payment-method-status {{ $method->is_active ? 'status-active' : 'status-inactive' }}">
                        </div>

                        <!-- Edit Link -->
                        <a href="{{ route('dashboard.dashboard.payment-methods.edit', $method) }}"
                            style="text-decoration: none; color: inherit; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <div class="payment-method-icon">
                                @if ($method->getFirstMediaUrl('payment_method_images'))
                                    @php $media = $method->getFirstMedia('payment_method_images'); @endphp
                                    <img src="{{ route('payment.methode.image', $media->id) }}"
                                        alt="{{ $method->getTranslation('name', app()->getLocale()) }}">
                                @else
                                    <i class="fas fa-credit-card"
                                        style="font-size: 2rem; color: var(--text-secondary);"></i>
                                @endif
                            </div>

                            <h3 class="payment-method-name">
                                {{ $method->getTranslation('name', app()->getLocale()) }}
                            </h3>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-credit-card"></i>
                <h3>{{ __('No Payment Methods') }}</h3>
                <p>{{ __('You haven\'t added any payment methods yet. Add your first payment method to start accepting payments.') }}
                </p>
                <a href="{{ route('dashboard.dashboard.payment-methods.create') }}" class="add-button">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Payment Method') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal">
        <div class="delete-modal-content">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="delete-modal-title">{{ __('Confirm Delete') }}</h3>
            <p class="delete-modal-message">
                {{ __('Are you sure you want to delete this payment method? This action cannot be undone.') }}</p>
            <div class="delete-modal-actions">
                <button class="delete-modal-btn delete-modal-btn-cancel" onclick="closeDeleteModal()">
                    {{ __('Cancel') }}
                </button>
                <button class="delete-modal-btn delete-modal-btn-confirm" onclick="confirmDelete()">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPaymentMethodId = null;

        function deletePaymentMethod(paymentMethodId) {
            currentPaymentMethodId = paymentMethodId;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            currentPaymentMethodId = null;
        }

        function confirmDelete() {
            if (currentPaymentMethodId) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('dashboard.dashboard.payment-methods.destroy', ':id') }}'.replace(':id',
                    currentPaymentMethodId);

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method override for DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Initialize notifications
        document.addEventListener('DOMContentLoaded', function() {
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
            });
        });

        // Function to show custom notifications
        function showNotification(type, title, message) {
            const notification = document.createElement('div');
            notification.className = `notification ${type} professional-notification show`;

            const iconMap = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };

            notification.innerHTML = `
                    <div class="notification-icon">
                        <i class="${iconMap[type]}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                    <div class="notification-progress"></div>
                `;

            document.body.appendChild(notification);

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
        }
    </script>
@endpush
