@extends('core::dashboard.layouts.app')

@section('title', __('Edit Group') . ' - ' . $group->name)

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Edit Group Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .edit-group-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .edit-group-header {
            margin-bottom: 2rem;
        }

        .edit-group-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.5rem 0;
        }

        .edit-group-subtitle {
            color: #a0a0a0;
            font-size: 1rem;
        }

        .form-container {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #404040;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-input:disabled {
            background: #1f2937;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-help {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-primary {
            background: #f59e0b;
            color: #000000;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #d97706;
        }

        .btn-secondary {
            background: #6b7280;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .default-notice {
            background: #1e40af;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #ffffff;
        }

        .default-notice i {
            color: #60a5fa;
            margin-left: 0.5rem;
        }

        .delete-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #404040;
            text-align: center;
        }

        html[data-theme="light"] .delete-section,
        html[data-theme="light"] body .delete-section {
            border-top: 1px solid #e5e7eb !important;
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
    <div class="edit-group-container">
        <!-- Header -->
        <div class="edit-group-header">
            <h1 class="edit-group-title">{{ __('Edit Group') }}</h1>
            <p class="edit-group-subtitle">{{ __('Update group information and settings') }}</p>
        </div>

        <!-- Form -->
        <form class="form-container" method="POST"
            action="{{ LaravelLocalization::localizeURL(route('admin.groups.update', $group->id)) }}">
            @csrf
            @method('PUT')

            @if ($group->is_default)
                <div class="default-notice">
                    <i class="fas fa-info-circle"></i>
                    {{ __('This is the default group. You can modify its settings.') }}
                </div>
            @endif

            <!-- Group Name -->
            <div class="form-group">
                <label class="form-label">{{ __('Group Name') }}</label>
                <input type="text" class="form-input @error('name') error @enderror" name="name"
                    value="{{ old('name', $group->name) }}" placeholder="{{ __('Enter group name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Choose a descriptive name for the group') }}</div>
            </div>

            <!-- Profit Percentage -->
            <div class="form-group">
                <label class="form-label">{{ __('Profit Percentage') }}</label>
                <input type="number" class="form-input @error('profit_percentage') error @enderror"
                    name="profit_percentage" value="{{ old('profit_percentage', $group->profit_percentage) }}"
                    placeholder="0.00" step="0.01" min="0" max="100" required>
                @error('profit_percentage')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Enter profit percentage (0-100)') }}</div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.index')) }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Update Group') }}
                </button>
            </div>
        </form>

        <!-- Delete Button (Outside Edit Form) -->
        @if (!$group->is_default)
        <div class="delete-section">
            <button type="button" class="btn-danger" onclick="openDeleteModal({{ $group->id }}, '{{ addslashes($group->name) }}')">
                <i class="fas fa-trash"></i>
                {{ __('Delete Group') }}
            </button>
        </div>
        @endif
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
