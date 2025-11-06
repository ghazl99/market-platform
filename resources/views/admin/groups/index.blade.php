@extends('core::dashboard.layouts.app')

@section('title', __('Groups Management'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Groups Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .groups-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .groups-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .groups-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .add-group-btn {
            background: #f59e0b;
            color: #000000;
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .add-group-btn:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .group-card {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #404040;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .group-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: #f59e0b;
        }

        .group-card-content {
            flex: 1;
        }

        .group-card-content:hover {
            cursor: pointer;
        }

        .group-card.default {
            border-color: #10b981;
        }

        .group-card.default::before {
            content: 'افتراضي';
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #10b981;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .group-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .group-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .profit-percentage {
            background: #1e40af;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .users-count {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .group-actions {
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #404040;
        }

        .group-card:hover .group-actions {
            opacity: 1;
        }

        html[data-theme="light"] .group-actions,
        html[data-theme="light"] body .group-actions {
            border-top: 1px solid #e5e7eb !important;
        }

        .action-btn {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            padding: 0.5rem;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            position: relative;
            z-index: 10;
        }

        .action-btn:hover {
            background: #4b5563;
        }

        .action-btn.edit:hover {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .action-btn.delete:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .add-group-card {
            background: #2d2d2d;
            border: 2px dashed #555;
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
        }

        .add-group-card:hover {
            border-color: #f59e0b;
            background: #374151;
        }

        .add-group-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .add-group-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .add-group-subtitle {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .groups-grid {
                grid-template-columns: 1fr;
            }

            .groups-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .add-group-btn {
                justify-content: center;
            }
        }

        /* Light Mode Styles - Maximum Priority */
        html[data-theme="light"] body,
        html[data-theme="light"] body .groups-container {
            background: #ffffff !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .groups-title,
        html[data-theme="light"] body .groups-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .group-card,
        html[data-theme="light"] body .group-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .group-card:hover,
        html[data-theme="light"] body .group-card:hover {
            background: #f9fafb !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            border-color: #f59e0b !important;
        }

        html[data-theme="light"] .group-card.default,
        html[data-theme="light"] body .group-card.default {
            border-color: #10b981 !important;
        }

        html[data-theme="light"] .group-name,
        html[data-theme="light"] body .group-name {
            color: #111827 !important;
        }

        html[data-theme="light"] .users-count,
        html[data-theme="light"] body .users-count {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .action-btn,
        html[data-theme="light"] body .action-btn {
            background: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .action-btn:hover,
        html[data-theme="light"] body .action-btn:hover {
            background: #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.edit:hover,
        html[data-theme="light"] body .action-btn.edit:hover {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .action-btn.delete:hover,
        html[data-theme="light"] body .action-btn.delete:hover {
            background: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] .add-group-card,
        html[data-theme="light"] body .add-group-card {
            background: #ffffff !important;
            border: 2px dashed #d1d5db !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .add-group-card:hover,
        html[data-theme="light"] body .add-group-card:hover {
            border-color: #f59e0b !important;
            background: #f9fafb !important;
        }

        html[data-theme="light"] .add-group-subtitle,
        html[data-theme="light"] body .add-group-subtitle {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .no-groups,
        html[data-theme="light"] body .no-groups {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .no-groups h3,
        html[data-theme="light"] body .no-groups h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .no-groups i,
        html[data-theme="light"] body .no-groups i {
            color: #d1d5db !important;
        }

        /* Delete Modal Styles */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .delete-modal-overlay.show {
            display: flex;
        }

        .delete-modal {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            border: 1px solid #404040;
            animation: modalSlideIn 0.3s ease-out;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #404040;
        }

        .delete-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .delete-modal-title i {
            color: #ef4444;
            font-size: 1.5rem;
        }

        .delete-modal-close {
            background: none;
            border: none;
            color: #cccccc;
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
            margin-bottom: 1.5rem;
        }

        .delete-modal-warning {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .delete-modal-warning i {
            color: #ef4444;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .delete-modal-warning-text {
            color: #ffffff;
            line-height: 1.6;
        }

        .delete-modal-warning-text strong {
            color: #ef4444;
        }

        .delete-modal-group-info {
            background: #1a1a1a;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .delete-modal-group-info-label {
            color: #a0a0a0;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .delete-modal-group-info-value {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .delete-modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .delete-modal-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .delete-modal-btn-cancel {
            background: #404040;
            color: #ffffff;
        }

        .delete-modal-btn-cancel:hover {
            background: #555555;
        }

        .delete-modal-btn-confirm {
            background: #ef4444;
            color: #ffffff;
        }

        .delete-modal-btn-confirm:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        /* Light Mode for Modal */
        html[data-theme="light"] .delete-modal,
        html[data-theme="light"] body .delete-modal {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .delete-modal-header,
        html[data-theme="light"] body .delete-modal-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .delete-modal-title,
        html[data-theme="light"] body .delete-modal-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-close,
        html[data-theme="light"] body .delete-modal-close {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .delete-modal-close:hover,
        html[data-theme="light"] body .delete-modal-close:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-warning,
        html[data-theme="light"] body .delete-modal-warning {
            background: rgba(239, 68, 68, 0.1) !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
        }

        html[data-theme="light"] .delete-modal-warning-text,
        html[data-theme="light"] body .delete-modal-warning-text {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-group-info,
        html[data-theme="light"] body .delete-modal-group-info {
            background: #f9fafb !important;
        }

        html[data-theme="light"] .delete-modal-group-info-label,
        html[data-theme="light"] body .delete-modal-group-info-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .delete-modal-group-info-value,
        html[data-theme="light"] body .delete-modal-group-info-value {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-modal-btn-cancel,
        html[data-theme="light"] body .delete-modal-btn-cancel {
            background: #f3f4f6 !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .delete-modal-btn-cancel:hover,
        html[data-theme="light"] body .delete-modal-btn-cancel:hover {
            background: #e5e7eb !important;
        }
    </style>
@endpush

@section('content')
    <div class="groups-container">
        <!-- Groups Header -->
        <div class="groups-header">
            <h1 class="groups-title">{{ __('Groups Management') }}</h1>
            <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.create')) }}" class="add-group-btn">
                <i class="fas fa-plus"></i>
                {{ __('Add New Group') }}
            </a>
        </div>

        <!-- Groups Grid -->
        <div class="groups-grid">
            <!-- Add New Group Card -->
            <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.create')) }}" class="add-group-card">
                <div class="add-group-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="add-group-text">{{ __('New Group') }}</div>
                <div class="add-group-subtitle">{{ __('Add') }}</div>
            </a>

            <!-- Existing Groups -->
            @forelse($groups as $group)
                <div class="group-card {{ $group->is_default ? 'default' : '' }}">
                    <div class="group-card-content" 
                         onclick="window.location.href='{{ LaravelLocalization::localizeURL(route('admin.groups.show', $group->id)) }}'"
                         style="cursor: pointer;">
                        <div class="group-name">{{ $group->name }}</div>
                        @if($group->store)
                            <div style="color: #a0a0a0; font-size: 0.9rem; margin-bottom: 1rem;">
                                <i class="fas fa-store"></i> {{ $group->store->name }}
                            </div>
                        @endif
                        <div class="group-stats">
                            <div class="profit-percentage">{{ $group->profit_percentage }}%</div>
                            <div class="users-count">{{ $group->users_count }} {{ __('users') }}</div>
                        </div>
                    </div>
                    <div class="group-actions" onclick="event.stopPropagation();">
                        <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.edit', $group->id)) }}"
                            class="action-btn edit" title="{{ __('Edit') }}" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if (!$group->is_default)
                            <button type="button" 
                                    class="action-btn delete" 
                                    title="{{ __('Delete') }}"
                                    onclick="event.stopPropagation(); openDeleteModal({{ $group->id }}, '{{ addslashes($group->name) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-groups" style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: #a0a0a0;">
                    <i class="fas fa-users" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <h3>{{ __('No Groups Found') }}</h3>
                    <p>{{ __('Start by creating your first group.') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal-overlay" onclick="if(event.target === this) closeDeleteModal()">
        <div class="delete-modal">
            <div class="delete-modal-header">
                <h3 class="delete-modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('Confirm Deletion') }}
                </h3>
                <button type="button" class="delete-modal-close" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="delete-modal-body">
                <div class="delete-modal-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="delete-modal-warning-text">
                        {{ __('Are you sure you want to delete this group?') }}
                        <br>
                        <strong>{{ __('This action cannot be undone.') }}</strong>
                        <br>
                        {{ __('All users in this group will be moved to the default group.') }}
                    </div>
                </div>
                <div class="delete-modal-group-info">
                    <div class="delete-modal-group-info-label">{{ __('Group Name') }}</div>
                    <div class="delete-modal-group-info-value" id="deleteModalGroupName"></div>
                </div>
            </div>
            <div class="delete-modal-footer">
                <button type="button" class="delete-modal-btn delete-modal-btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <form id="deleteGroupForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-modal-btn delete-modal-btn-confirm">
                        <i class="fas fa-trash"></i>
                        {{ __('Delete Group') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
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

            // Handle session messages with reliable notification display
            @if (session('success'))
                (function() {
                    function showSuccessNotification() {
                        try {
                            if (typeof window.showSuccess === 'function') {
                                window.showSuccess('{{ __('Success') }}', '{{ session('success') }}');
                                return true;
                            } else if (typeof showSuccess === 'function') {
                                showSuccess('{{ __('Success') }}', '{{ session('success') }}');
                                return true;
                            } else if (typeof window.showNotification === 'function') {
                                window.showNotification('success', '{{ __('Success') }}', '{{ session('success') }}');
                                return true;
                            }
                            return false;
                        } catch(e) {
                            console.error('Error in showSuccessNotification:', e);
                            return false;
                        }
                    }
                    
                    // Try immediately
                    if (!showSuccessNotification()) {
                        // Try again after delays
                        setTimeout(() => showSuccessNotification(), 200);
                        setTimeout(() => showSuccessNotification(), 500);
                        setTimeout(() => {
                            if (!showSuccessNotification()) {
                                // Final fallback: create notification manually
                                const container = document.getElementById('notification-container') || (() => {
                                    const div = document.createElement('div');
                                    div.id = 'notification-container';
                                    div.className = 'notification-container';
                                    document.body.appendChild(div);
                                    return div;
                                })();
                                
                                const notification = document.createElement('div');
                                notification.className = 'notification success';
                                notification.innerHTML = `
                                    <div class="notification-icon">✓</div>
                                    <div class="notification-content">
                                        <div class="notification-title">{{ __('Success') }}</div>
                                        <div class="notification-message">{{ session('success') }}</div>
                                    </div>
                                    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                                    <div class="notification-progress"></div>
                                `;
                                container.appendChild(notification);
                                setTimeout(() => notification.classList.add('show'), 100);
                                const progressBar = notification.querySelector('.notification-progress');
                                if (progressBar) {
                                    setTimeout(() => {
                                        progressBar.style.width = '100%';
                                        progressBar.style.transition = 'width 5000ms linear';
                                    }, 100);
                                }
                                setTimeout(() => {
                                    notification.classList.add('hide');
                                    setTimeout(() => notification.remove(), 400);
                                }, 5000);
                            }
                        }, 1000);
                    }
                })();
            @endif

            @if (session('error'))
                (function() {
                    function showErrorNotification() {
                        try {
                            if (typeof window.showError === 'function') {
                                window.showError('{{ __('Error') }}', '{{ session('error') }}');
                                return true;
                            } else if (typeof showError === 'function') {
                                showError('{{ __('Error') }}', '{{ session('error') }}');
                                return true;
                            } else if (typeof window.showNotification === 'function') {
                                window.showNotification('error', '{{ __('Error') }}', '{{ session('error') }}');
                                return true;
                            }
                            return false;
                        } catch(e) {
                            console.error('Error in showErrorNotification:', e);
                            return false;
                        }
                    }
                    
                    // Try immediately
                    if (!showErrorNotification()) {
                        // Try again after delays
                        setTimeout(() => showErrorNotification(), 200);
                        setTimeout(() => showErrorNotification(), 500);
                        setTimeout(() => {
                            if (!showErrorNotification()) {
                                // Final fallback: create notification manually
                                const container = document.getElementById('notification-container') || (() => {
                                    const div = document.createElement('div');
                                    div.id = 'notification-container';
                                    div.className = 'notification-container';
                                    document.body.appendChild(div);
                                    return div;
                                })();
                                
                                const notification = document.createElement('div');
                                notification.className = 'notification error';
                                notification.innerHTML = `
                                    <div class="notification-icon">✕</div>
                                    <div class="notification-content">
                                        <div class="notification-title">{{ __('Error') }}</div>
                                        <div class="notification-message">{{ session('error') }}</div>
                                    </div>
                                    <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                                    <div class="notification-progress"></div>
                                `;
                                container.appendChild(notification);
                                setTimeout(() => notification.classList.add('show'), 100);
                                const progressBar = notification.querySelector('.notification-progress');
                                if (progressBar) {
                                    setTimeout(() => {
                                        progressBar.style.width = '100%';
                                        progressBar.style.transition = 'width 5000ms linear';
                                    }, 100);
                                }
                                setTimeout(() => {
                                    notification.classList.add('hide');
                                    setTimeout(() => notification.remove(), 400);
                                }, 5000);
                            }
                        }, 1000);
                    }
                })();
            @endif
        });

        // Delete Modal Functions
        function openDeleteModal(groupId, groupName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteGroupForm');
            const groupNameElement = document.getElementById('deleteModalGroupName');
            
            // Set form action
            form.action = '{{ LaravelLocalization::localizeURL(route("admin.groups.destroy", ":id")) }}'.replace(':id', groupId);
            
            // Set group name
            groupNameElement.textContent = groupName;
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Handle form submission
        document.getElementById('deleteGroupForm')?.addEventListener('submit', function(e) {
            // Form will submit normally, server will handle the deletion
            // Success notification will be shown from session message
        });
    </script>
@endpush
