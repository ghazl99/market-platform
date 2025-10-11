@extends('core::dashboard.layouts.app')

@section('title', __('Manage Permissions'))
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
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

        /* Staff Permissions Styles - Dark Theme */
        .staff-container {
            padding: 2rem;
            background: #1f2937;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #374151;
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
        }

        .back-btn {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(107, 114, 128, 0.2);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(107, 114, 128, 0.3);
            color: white;
            text-decoration: none;
        }

        .staff-info-card {
            background: #374151;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .staff-avatar img {
            border-radius: 50%;
            border: 3px solid #6b7280;
        }

        .avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 2rem;
            border: 3px solid #6b7280;
        }

        .staff-details h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 0.5rem 0;
        }

        .staff-details p {
            color: #d1d5db;
            margin: 0 0 0.5rem 0;
        }

        .staff-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .staff-status.verified {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .staff-status.unverified {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .permissions-form-container {
            background: #374151;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            overflow: hidden;
        }

        .form-header {
            padding: 2rem;
            border-bottom: 1px solid #4b5563;
            background: #4b5563;
        }

        .form-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 0.5rem 0;
        }

        .form-header p {
            color: #d1d5db;
            margin: 0;
        }

        .permissions-form {
            padding: 2rem;
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
            border: 1px solid #6b7280;
            border-radius: 8px;
            font-size: 0.9rem;
            background: #1f2937;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-text {
            color: #d1d5db;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #4b5563;
        }

        .submit-btn {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(5, 150, 105, 0.3);
        }

        .cancel-btn {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(107, 114, 128, 0.2);
        }

        .cancel-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(107, 114, 128, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .staff-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .staff-info-card {
                flex-direction: column;
                text-align: center;
            }

            .form-actions {
                flex-direction: column;
            }

            .permissions-form {
                padding: 1rem;
            }

            .form-header {
                padding: 1rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="staff-container">
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
            <h1 class="page-title">{{ __('إدارة صلاحيات الموظف') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.staff.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('العودة لقائمة الموظفين') }}
                </a>
            </div>
        </div>

        <!-- Staff Info -->
        <div class="staff-info-card">
            <div class="staff-avatar">
                @if ($user->profile_photo_url ?? false)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle"
                        width="60" height="60">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="staff-details">
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->email }}</p>
                @php
                    $store = \Modules\Store\Models\Store::currentFromUrl()->first();
                    $isActive = $user->stores->firstWhere('id', $store->id)?->pivot->is_active ?? false;
                @endphp
                <span class="staff-status {{ $isActive ? 'verified' : 'unverified' }}">
                    {{ $isActive ? __('متفعل') : __('غير متفعل') }}
                </span>
            </div>
        </div>

        <!-- Permissions Form -->
        <div class="permissions-form-container">
            <div class="form-header">
                <h3>{{ __('إدارة الصلاحيات') }}</h3>
                <p>{{ __('اختر الصلاحيات المناسبة لهذا الموظف') }}</p>
            </div>
            <form method="POST" action="{{ route('dashboard.staff.update', $user->id) }}" class="permissions-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="permissions" class="form-label">
                        <i class="fas fa-shield-alt"></i>
                        {{ __('الصلاحيات') }}
                    </label>
                    <select id="permissions" name="permissions[]" class="form-control" multiple="multiple"
                        data-placeholder="{{ __('اختر أو أضف صلاحيات...') }}">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}"
                                {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text">{{ __('يمكنك اختيار عدة صلاحيات للموظف') }}</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        {{ __('حفظ الصلاحيات') }}
                    </button>
                    <a href="{{ route('dashboard.staff.index') }}" class="cancel-btn">
                        <i class="fas fa-times"></i>
                        {{ __('إلغاء') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        document.addEventListener('DOMContentLoaded', function() {
            $('#permissions').select2({
                // theme: 'bootstrap-5',
                placeholder: "{{ __('Select or add permissions...') }}",

                width: '100%',
            });
        });
    </script>
@endpush
