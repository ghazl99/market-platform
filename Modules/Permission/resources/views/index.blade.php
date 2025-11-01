@extends('core::dashboard.layouts.app')

@section('title', __('الصلاحيات'))

@section('content')
    <div class="permissions-container">
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
            <h1 class="page-title">{{ __('إدارة الصلاحيات') }}</h1>
            <div class="page-actions">
                <button class="add-permission-btn" onclick="openAddPermissionModal()">
                    <i class="fas fa-plus"></i>
                    {{ __('إضافة صلاحية جديدة') }}
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="permissions-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-number">{{ $permissions->count() }}</div>
                <div class="stat-label">{{ __('إجمالي الصلاحيات') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon roles">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="stat-number">{{ $roles->count() }}</div>
                <div class="stat-label">{{ __('الأدوار') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $users->count() }}</div>
                <div class="stat-label">{{ __('المستخدمين') }}</div>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="permissions-table-container">
            <div class="table-header">
                <h3>{{ __('قائمة الصلاحيات') }}</h3>
                <div class="table-actions">
                    <input type="text" id="permissions-search" placeholder="{{ __('البحث في الصلاحيات...') }}"
                        class="search-input">
                </div>
            </div>

            <div class="table-responsive">
                <table class="permissions-table">
                    <thead>
                        <tr>
                            <th>{{ __('اسم الصلاحية') }}</th>
                            <th>{{ __('نوع الحماية') }}</th>
                            <th>{{ __('تاريخ الإنشاء') }}</th>
                            <th>{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr data-permission-id="{{ $permission->id }}">
                                <td>
                                    <div class="permission-name">
                                        <i class="fas fa-key"></i>
                                        <span>{{ $permission->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="guard-badge">{{ $permission->guard_name }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ $permission->created_at->format('Y-m-d H:i') }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn edit"
                                            onclick="editPermission({{ $permission->id }}, '{{ $permission->name }}', '{{ $permission->guard_name }}')">
                                            <i class="fas fa-edit"></i>
                                            {{ __('تعديل') }}
                                        </button>
                                        <button class="action-btn delete"
                                            onclick="deletePermission({{ $permission->id }}, '{{ $permission->name }}')">
                                            <i class="fas fa-trash"></i>
                                            {{ __('حذف') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center empty-state">
                                    <i class="fas fa-shield-alt"></i>
                                    <p>{{ __('لا توجد صلاحيات متاحة') }}</p>
                                    <button class="add-permission-btn" onclick="openAddPermissionModal()">
                                        <i class="fas fa-plus"></i>
                                        {{ __('إضافة أول صلاحية') }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Permission Modal -->
    <div id="permissionModal" class="permission-modal" style="display: none;">
        <div class="modal-overlay" onclick="closePermissionModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">{{ __('إضافة صلاحية جديدة') }}</h3>
                <button class="modal-close" onclick="closePermissionModal()">&times;</button>
            </div>
            <form id="permissionForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permissionName">{{ __('اسم الصلاحية') }}</label>
                        <input type="text" id="permissionName" name="name" class="form-control" required>
                        <small class="form-text">{{ __('مثال: create-users, edit-products, delete-orders') }}</small>
                    </div>
                    <div class="form-group">
                        <label for="guardName">{{ __('نوع الحماية') }}</label>
                        <select id="guardName" name="guard_name" class="form-control" required>
                            <option value="web">{{ __('Web') }}</option>
                            <option value="api">{{ __('API') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePermissionModal()">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('حفظ') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('تأكيد الحذف') }}</h3>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="delete-message">
                    {{ __('هل أنت متأكد من حذف الصلاحية') }} <strong id="permissionNameToDelete"></strong>؟
                </p>
                <p class="delete-warning">
                    {{ __('هذا الإجراء لا يمكن التراجع عنه.') }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    {{ __('إلغاء') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i>
                    {{ __('حذف الصلاحية') }}
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">
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
            position: relative;
            background: rgba(17, 24, 39, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 15px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 4px solid;
            overflow: hidden;
            backdrop-filter: blur(10px);
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
            border-left-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
        }

        .notification.error {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        .notification.warning {
            border-left-color: #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
        }

        .notification.info {
            border-left-color: #3b82f6;
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
            background: rgba(255, 255, 255, 0.1);
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
            border-left: none;
            border-right: 4px solid;
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

        .permissions-container {
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

        .add-permission-btn {
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
        }

        .add-permission-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }

        .permissions-stats {
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

        .stat-icon.roles {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-icon.users {
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

        .permissions-table-container {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
            margin: 0;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
            min-width: 250px;
        }

        .search-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .permissions-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .permissions-table th {
            background: var(--bg-secondary, #f9fafb);
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
        }

        .permissions-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light, #f3f4f6);
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
        }

        .permissions-table tr:hover {
            background: var(--bg-secondary, #f9fafb);
        }

        .permission-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .permission-name i {
            color: #059669;
            font-size: 0.9rem;
        }

        .guard-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .date-text {
            color: var(--text-secondary, #6b7280);
            font-size: 0.8rem;
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

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-light, #9ca3af);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--text-secondary, #6b7280);
            margin-bottom: 1.5rem;
        }

        /* Modal Styles */
        .permission-modal,
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
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: #1f2937;
            border-radius: 16px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 10000;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            transform: translateY(-20px) scale(0.95);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .permission-modal.show .modal-content,
        .delete-modal.show .modal-content {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .modal-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid #374151;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: #ffffff;
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: #9ca3af;
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

        .modal-close:hover {
            background: #374151;
            color: #ffffff;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #4b5563;
            border-radius: 8px;
            background: #374151;
            color: #ffffff;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
        }

        .form-control option {
            background: #374151;
            color: #ffffff;
        }

        .form-text {
            color: #9ca3af;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .modal-footer {
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
            background: #4b5563;
            color: #ffffff;
            border: 1px solid #6b7280;
        }

        .btn-secondary:hover {
            background: #6b7280;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #059669;
            color: white;
        }

        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
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
            text-align: center;
        }

        .delete-message strong {
            color: #ef4444;
        }

        .delete-warning {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-bottom: 0;
            text-align: center;
        }

        /* Light Mode Styles */
        html[data-theme="light"] .permissions-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .stat-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        html[data-theme="light"] .stat-number {
            color: #111827 !important;
        }

        html[data-theme="light"] .stat-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .permissions-table-container {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .permissions-table th {
            background: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .permissions-table td {
            border-bottom: 1px solid #f3f4f6 !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .permissions-table tr:hover {
            background: #f9fafb !important;
        }

        html[data-theme="light"] .search-input {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .action-btn.edit {
            background: #f9fafb !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.edit:hover {
            background: #059669 !important;
            color: white !important;
        }

        html[data-theme="light"] .modal-content {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .modal-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .modal-header h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .modal-close {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .modal-close:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-group label {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-control {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-control:focus {
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-control option {
            background: #ffffff !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-text {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .delete-message {
            color: #111827 !important;
        }

        html[data-theme="light"] .delete-warning {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .empty-state p {
            color: #374151 !important;
        }

        html[data-theme="light"] .empty-state i {
            color: #9ca3af !important;
        }

        /* Dark Mode Styles */
        html[data-theme="dark"] .permissions-container {
            background: #1a1a1a !important;
        }

        html[data-theme="dark"] .stat-card {
            background: #1f2937 !important;
            border: 1px solid #374151 !important;
        }

        html[data-theme="dark"] .stat-number {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .stat-label {
            color: #d1d5db !important;
        }

        html[data-theme="dark"] .page-title {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .permissions-table-container {
            background: #1f2937 !important;
            border: 1px solid #374151 !important;
        }

        html[data-theme="dark"] .permissions-table th {
            background: #111827 !important;
            border-bottom: 1px solid #374151 !important;
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .permissions-table td {
            border-bottom: 1px solid #374151 !important;
            color: #d1d5db !important;
        }

        html[data-theme="dark"] .permissions-table tr:hover {
            background: #111827 !important;
        }

        html[data-theme="dark"] .search-input {
            background: #1f2937 !important;
            border: 1px solid #374151 !important;
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .action-btn.edit {
            background: #374151 !important;
            color: #d1d5db !important;
            border: 1px solid #4b5563 !important;
        }

        html[data-theme="dark"] .action-btn.edit:hover {
            background: #059669 !important;
            color: white !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .permissions-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .permissions-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem;
            }

            .modal-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Professional Notification System
        document.addEventListener('DOMContentLoaded', function() {
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

        let currentPermissionId = null;
        let currentDeleteForm = null;

        // Open Add Permission Modal
        function openAddPermissionModal() {
            document.getElementById('modalTitle').textContent = '{{ __('إضافة صلاحية جديدة') }}';
            document.getElementById('permissionForm').action = '{{ route('permission.store') }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('permissionName').value = '';
            document.getElementById('guardName').value = 'web';

            const modal = document.getElementById('permissionModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        // Edit Permission
        function editPermission(id, name, guardName) {
            currentPermissionId = id;
            document.getElementById('modalTitle').textContent = '{{ __('تعديل الصلاحية') }}';
            document.getElementById('permissionForm').action = '{{ route('permission.update', ':id') }}'.replace(':id',
                id);
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('permissionName').value = name;
            document.getElementById('guardName').value = guardName;

            const modal = document.getElementById('permissionModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        // Close Permission Modal
        function closePermissionModal() {
            const modal = document.getElementById('permissionModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
            currentPermissionId = null;
        }

        // Delete Permission
        function deletePermission(id, name) {
            document.getElementById('permissionNameToDelete').textContent = name;
            currentPermissionId = id;

            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        // Close Delete Modal
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
            currentPermissionId = null;
        }

        // Confirm Delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentPermissionId) {
                // Create form for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('permission.destroy', ':id') }}'.replace(':id', currentPermissionId);
                form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Search functionality
        document.getElementById('permissions-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.permissions-table tbody tr');

            rows.forEach(row => {
                const permissionName = row.querySelector('.permission-name span').textContent.toLowerCase();
                if (permissionName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endpush
