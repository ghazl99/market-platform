@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Add New Staff'))
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
            <h1 class="page-title">{{ __('إضافة موظف جديد') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.staff.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('العودة لقائمة الموظفين') }}
                </a>
            </div>
        </div>

        <!-- Add Staff Form -->
        <div class="staff-form-container">
            <div class="form-header">
                <h3>{{ __('بيانات الموظف الجديد') }}</h3>
                <p>{{ __('املأ البيانات التالية لإضافة موظف جديد') }}</p>
            </div>

            <form action="{{ route('dashboard.staff.store') }}" method="POST" enctype="multipart/form-data"
                class="staff-form">
                @csrf
                <input type="hidden" name="role" value="staff">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            {{ __('الاسم الكامل') }}
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            {{ __('البريد الإلكتروني') }}
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            {{ __('كلمة المرور') }}
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            {{ __('تأكيد كلمة المرور') }}
                        </label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="profile_photo" class="form-label">
                        <i class="fas fa-image"></i>
                        {{ __('الصورة الشخصية') }}
                    </label>
                    <div class="file-upload">
                        <input type="file" class="file-upload-input @error('profile_photo') is-invalid @enderror"
                            id="profile_photo" name="profile_photo" accept="image/*">
                        <label for="profile_photo" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span class="file-upload-text">{{ __('Drag image here or click to select') }}</span>
                            <span class="file-upload-hint">{{ __('You can upload image (JPG, PNG, GIF, WEBP)') }}</span>
                        </label>
                    </div>
                    <div class="image-preview" id="imagePreview"></div>
                    <small class="form-text">{{ __('اختياري - يمكن رفع صورة شخصية للموظف') }}</small>
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        {{ __('حفظ الموظف') }}
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

        /* Staff Form Styles - Dark Theme */
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

        .staff-form-container {
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

        .staff-form {
            padding: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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
            background: #1f2937 !important;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: #1f2937 !important;
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: #9ca3af !important;
        }

        /* Dark Mode Styles - Force apply for all input types */
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] input.form-control,
        html[data-theme="dark"] input[type="email"].form-control,
        html[data-theme="dark"] input[type="password"].form-control,
        html[data-theme="dark"] input[type="text"].form-control {
            background: #1f2937 !important;
            color: #ffffff !important;
            border-color: #6b7280 !important;
        }

        html[data-theme="dark"] .form-control:focus,
        html[data-theme="dark"] input.form-control:focus,
        html[data-theme="dark"] input[type="email"].form-control:focus,
        html[data-theme="dark"] input[type="password"].form-control:focus,
        html[data-theme="dark"] input[type="text"].form-control:focus {
            background: #1f2937 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        .form-text {
            color: #d1d5db;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* File Upload Styles */
        .file-upload {
            position: relative;
            display: block;
            cursor: pointer;
            width: 100%;
            margin-top: 0.5rem;
        }

        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 1;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            border: 2px dashed #6b7280;
            border-radius: 12px;
            background: #1f2937;
            color: #d1d5db;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 200px;
            text-align: center;
            position: relative;
        }

        .file-upload-label:hover {
            border-color: #059669;
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
        }

        .file-upload-label.dragover {
            border-color: #059669;
            background: rgba(5, 150, 105, 0.15);
            color: #059669;
        }

        .file-upload-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: inherit;
        }

        .file-upload-text {
            font-size: 0.9rem;
            font-weight: 500;
            color: inherit;
        }

        .file-upload-hint {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            opacity: 0.7;
            color: inherit;
        }

        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #1f2937;
        }

        .preview-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .preview-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .preview-remove:hover {
            background: #ef4444;
            transform: scale(1.1);
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .staff-form {
                padding: 1rem;
            }

            .form-header {
                padding: 1rem;
            }
        }

        /* Light Mode Styles - Maximum Priority */
        html[data-theme="light"] .staff-container,
        html[data-theme="light"] body .staff-container {
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

        html[data-theme="light"] .staff-form-container,
        html[data-theme="light"] body .staff-form-container {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .form-header,
        html[data-theme="light"] body .form-header {
            background: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .form-header h3,
        html[data-theme="light"] body .form-header h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-header p,
        html[data-theme="light"] body .form-header p {
            color: #6b7280 !important;
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

        html[data-theme="light"] .form-text,
        html[data-theme="light"] body .form-text {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .form-actions,
        html[data-theme="light"] body .form-actions {
            border-top: 1px solid #e5e7eb !important;
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

        /* Light Mode File Upload */
        html[data-theme="light"] .file-upload-label,
        html[data-theme="light"] body .file-upload-label {
            background: #ffffff !important;
            border-color: #e5e7eb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .file-upload-label:hover,
        html[data-theme="light"] body .file-upload-label:hover {
            background: #f9fafb !important;
            border-color: #059669 !important;
            color: #059669 !important;
        }

        html[data-theme="light"] .file-upload-icon,
        html[data-theme="light"] .file-upload-text,
        html[data-theme="light"] .file-upload-hint,
        html[data-theme="light"] body .file-upload-icon,
        html[data-theme="light"] body .file-upload-text,
        html[data-theme="light"] body .file-upload-hint {
            color: inherit !important;
        }

        html[data-theme="light"] .file-upload-label.dragover,
        html[data-theme="light"] body .file-upload-label.dragover {
            background: rgba(5, 150, 105, 0.05) !important;
            border-color: #059669 !important;
            color: #059669 !important;
        }

        /* Dark Mode File Upload */
        html[data-theme="dark"] .file-upload-label,
        html[data-theme="dark"] body .file-upload-label {
            background: #1f2937 !important;
            border-color: #6b7280 !important;
            color: #d1d5db !important;
        }

        html[data-theme="dark"] .file-upload-label:hover,
        html[data-theme="dark"] body .file-upload-label:hover {
            background: rgba(5, 150, 105, 0.1) !important;
            border-color: #059669 !important;
            color: #059669 !important;
        }

        html[data-theme="dark"] .file-upload-label.dragover,
        html[data-theme="dark"] body .file-upload-label.dragover {
            background: rgba(5, 150, 105, 0.15) !important;
            border-color: #059669 !important;
            color: #059669 !important;
        }
    </style>
@endpush

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

            // Image upload preview with drag & drop support
            const imageInput = document.getElementById('profile_photo');
            const imagePreview = document.getElementById('imagePreview');

            if (imageInput && imagePreview) {
                const fileUploadLabel = document.querySelector('.file-upload-label');

                // Handle file input change
                imageInput.addEventListener('change', function(e) {
                    handleFileSelect(e.target.files[0]);
                });

                // Drag and drop support
                if (fileUploadLabel) {
                    // Prevent default drag behaviors
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, preventDefaults, false);
                        document.body.addEventListener(eventName, preventDefaults, false);
                    });

                    // Highlight drop area when item is dragged over it
                    ['dragenter', 'dragover'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        fileUploadLabel.addEventListener(eventName, unhighlight, false);
                    });

                    // Handle dropped files
                    fileUploadLabel.addEventListener('drop', handleDrop, false);

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    function highlight() {
                        fileUploadLabel.classList.add('dragover');
                    }

                    function unhighlight() {
                        fileUploadLabel.classList.remove('dragover');
                    }

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;
                        if (files.length > 0) {
                            imageInput.files = files;
                            handleFileSelect(files[0]);
                        }
                    }
                }

                function handleFileSelect(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.innerHTML = `
                                <div class="preview-item">
                                    <img src="${e.target.result}" alt="Preview" class="preview-image">
                                    <button type="button" class="preview-remove" onclick="removePreview()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                }

                window.removePreview = function() {
                    imageInput.value = '';
                    imagePreview.innerHTML = '';
                };
            }
        });
    </script>
@endpush
