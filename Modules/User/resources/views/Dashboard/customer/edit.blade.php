@extends('core::dashboard.layouts.app')

@section('title', __('Edit Customer'))

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
    <div class="user-edit-container">
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
            <h1 class="page-title">{{ __('Edit Customer') }}: {{ $customer->name }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.customer.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Customers') }}
                </a>
            </div>
        </div>

        <form class="form-container" id="userForm" method="POST"
            action="{{ route('dashboard.customer.update', $customer->id) }}">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Personal Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Full Name') }}</label>
                        <input type="text" class="form-input @error('name') is-invalid @enderror" name="name"
                            placeholder="{{ __('Enter full name') }}" value="{{ old('name', $customer->name) }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Name as it will appear in the system') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">{{ __('Email Address') }}</label>
                        <input type="email" class="form-input @error('email') is-invalid @enderror" name="email"
                            placeholder="user@example.com" value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Will be used for login') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Phone Number') }}</label>
                        <input type="tel" class="form-input @error('phone') is-invalid @enderror" name="phone"
                            placeholder="+966501234567" value="{{ old('phone', $customer->phone) }}">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Phone number with country code') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Birth Date') }}</label>
                        <input type="date" class="form-input @error('birth_date') is-invalid @enderror" name="birth_date"
                            value="{{ old('birth_date', $customer->birth_date ? $customer->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Account Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('Password') }}
                            <small>({{ __('Leave blank to keep current password') }})</small></label>
                        <input type="password" class="form-input @error('password') is-invalid @enderror" name="password"
                            id="password" placeholder="{{ __('New password') }}">
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthText">{{ __('Enter new password') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-input @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="{{ __('Confirm new password') }}">
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">{{ __('Role') }}</label>
                        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                            <option value="">{{ __('Choose Role') }}</option>
                            <option value="customer"
                                {{ old('role', $customer->roles->first()->name ?? '') == 'customer' ? 'selected' : '' }}>
                                {{ __('Customer') }}</option>
                            <option value="staff"
                                {{ old('role', $customer->roles->first()->name ?? '') == 'staff' ? 'selected' : '' }}>
                                {{ __('Staff') }}
                            </option>
                            <option value="owner"
                                {{ old('role', $customer->roles->first()->name ?? '') == 'owner' ? 'selected' : '' }}>
                                {{ __('Store Owner') }}
                            </option>
                        </select>
                        @error('role')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Define user permissions') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active"
                                {{ old('status', $customer->email_verified_at ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="inactive"
                                {{ old('status', $customer->email_verified_at ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                            <option value="pending"
                                {{ old('status', $customer->email_verified_at ? 'active' : 'inactive') == 'pending' ? 'selected' : '' }}>
                                {{ __('Pending') }}
                            </option>
                        </select>
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Additional Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('Address') }}</label>
                        <input type="text" class="form-input @error('address') is-invalid @enderror" name="address"
                            placeholder="{{ __('Full address') }}" value="{{ old('address', $customer->address) }}">
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('City') }}</label>
                        <input type="text" class="form-input @error('city') is-invalid @enderror" name="city"
                            placeholder="{{ __('City') }}" value="{{ old('city', $customer->city) }}">
                        @error('city')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Postal Code') }}</label>
                        <input type="text" class="form-input @error('postal_code') is-invalid @enderror"
                            name="postal_code" placeholder="12345"
                            value="{{ old('postal_code', $customer->postal_code) }}">
                        @error('postal_code')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Country') }}</label>
                        <select class="form-select @error('country') is-invalid @enderror" name="country">
                            <option value="">{{ __('Choose Country') }}</option>
                            <option value="SA"
                                {{ old('country', $customer->country ?? 'SA') == 'SA' ? 'selected' : '' }}>
                                {{ __('Saudi Arabia') }}</option>
                            <option value="AE"
                                {{ old('country', $customer->country ?? '') == 'AE' ? 'selected' : '' }}>
                                {{ __('UAE') }}
                            </option>
                            <option value="KW"
                                {{ old('country', $customer->country ?? '') == 'KW' ? 'selected' : '' }}>
                                {{ __('Kuwait') }}
                            </option>
                            <option value="QA"
                                {{ old('country', $customer->country ?? '') == 'QA' ? 'selected' : '' }}>
                                {{ __('Qatar') }}
                            </option>
                            <option value="BH"
                                {{ old('country', $customer->country ?? '') == 'BH' ? 'selected' : '' }}>
                                {{ __('Bahrain') }}
                            </option>
                            <option value="OM"
                                {{ old('country', $customer->country ?? '') == 'OM' ? 'selected' : '' }}>
                                {{ __('Oman') }}
                            </option>
                        </select>
                        @error('country')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Debt Limit') }}</label>
                        <input type="number" step="0.01" min="0"
                            class="form-input @error('debt_limit') is-invalid @enderror" name="debt_limit"
                            placeholder="0.00" value="{{ old('debt_limit', $customer->debt_limit ?? 0) }}">
                        @error('debt_limit')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Leave 0 for no credit allowed') }}</div>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Preferences') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('Preferred Language') }}</label>
                        <select class="form-select @error('language') is-invalid @enderror" name="language">
                            <option value="ar"
                                {{ old('language', $customer->language ?? 'ar') == 'ar' ? 'selected' : '' }}>
                                {{ __('Arabic') }}</option>
                            <option value="en"
                                {{ old('language', $customer->language ?? '') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                        @error('language')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Timezone') }}</label>
                        <select class="form-select @error('timezone') is-invalid @enderror" name="timezone">
                            <option value="Asia/Riyadh"
                                {{ old('timezone', $customer->timezone ?? 'Asia/Riyadh') == 'Asia/Riyadh' ? 'selected' : '' }}>
                                {{ __('Riyadh (GMT+3)') }}</option>
                            <option value="Asia/Dubai"
                                {{ old('timezone', $customer->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>
                                {{ __('Dubai (GMT+4)') }}</option>
                            <option value="Asia/Kuwait"
                                {{ old('timezone', $customer->timezone ?? '') == 'Asia/Kuwait' ? 'selected' : '' }}>
                                {{ __('Kuwait (GMT+3)') }}</option>
                        </select>
                        @error('timezone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group full-width">
                    <div class="form-checkbox">
                        <input type="checkbox" name="email_notifications" id="email_notifications"
                            {{ old('email_notifications', $customer->email_notifications) ? 'checked' : '' }}>
                        <label for="email_notifications"
                            class="form-checkbox-label">{{ __('Receive email notifications') }}</label>
                    </div>
                </div>
                <div class="form-group full-width">
                    <div class="form-checkbox">
                        <input type="checkbox" name="sms_notifications" id="sms_notifications"
                            {{ old('sms_notifications', $customer->sms_notifications) ? 'checked' : '' }}>
                        <label for="sms_notifications"
                            class="form-checkbox-label">{{ __('Receive SMS notifications') }}</label>
                    </div>
                </div>
            </div>

            <!-- Avatar Preview -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Profile Picture') }}</h3>
                <div class="avatar-preview">
                    <div class="avatar-preview-img" id="avatarPreview">{{ substr($customer->name, 0, 1) }}</div>
                    <div class="avatar-preview-text">
                        {{ __('Profile picture will be automatically generated from the first letter of the name') }}
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="cancelForm()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Update Customer') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <style>
        /* User Edit Page Specific Styles */
        .user-edit-container {
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

        .back-btn {
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-secondary, #6b7280);
            border: 1px solid var(--border-color, #e5e7eb);
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

        .form-container {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
            margin: 0 0 1.5rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #059669;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: -2px;
            right: 0;
            width: 50px;
            height: 2px;
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
        }

        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-label small {
            font-weight: normal;
            color: var(--text-secondary, #6b7280);
        }

        .form-input {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        .form-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-select.is-invalid {
            border-color: #ef4444;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .form-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #059669;
        }

        .form-checkbox-label {
            font-size: 0.9rem;
            color: var(--text-primary, #1f2937);
            cursor: pointer;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--bg-secondary, #f9fafb);
            border-radius: 2px;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: #ef4444;
            width: 25%;
        }

        .strength-medium {
            background: #f59e0b;
            width: 50%;
        }

        .strength-strong {
            background: #10b981;
            width: 75%;
        }

        .strength-very-strong {
            background: #10b981;
            width: 100%;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-light, #f3f4f6);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }

        .btn-secondary {
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-secondary, #6b7280);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .btn-secondary:hover {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .form-help {
            font-size: 0.8rem;
            color: var(--text-secondary, #6b7280);
            margin-top: 0.25rem;
        }

        .form-error {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 0.25rem;
        }

        .avatar-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .avatar-preview-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .avatar-preview-text {
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .user-edit-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-actions {
                justify-content: space-between;
            }

            .form-container {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // User edit page specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            const passwordInput = document.getElementById('password');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const avatarPreview = document.getElementById('avatarPreview');
            const nameInput = form.querySelector('input[name="name"]');

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                if (password) {
                    const strength = checkPasswordStrength(password);
                    strengthFill.className = 'strength-fill ' + strength.class;
                    strengthText.textContent = strength.text;
                } else {
                    strengthFill.className = 'strength-fill';
                    strengthText.textContent = '{{ __('Enter new password') }}';
                }

                // Remove error message when user starts typing
                const existingError = document.getElementById('dynamic-error-message');
                if (existingError) {
                    existingError.remove();
                }
            });

            // Avatar preview update
            nameInput.addEventListener('input', function() {
                const name = this.value.trim();
                if (name) {
                    avatarPreview.textContent = name.charAt(0).toUpperCase();
                } else {
                    avatarPreview.textContent = 'أ';
                }

                // Remove error message when user starts typing
                const existingError = document.getElementById('dynamic-error-message');
                if (existingError) {
                    existingError.remove();
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                // Get form data
                const formData = new FormData(form);
                const userData = Object.fromEntries(formData.entries());

                // Remove any existing error messages
                const existingError = document.getElementById('dynamic-error-message');
                if (existingError) {
                    existingError.remove();
                }

                // Basic validation - only check truly required fields
                if (!userData.name || !userData.email || !userData.role) {
                    e.preventDefault();
                    const missingFields = [];
                    if (!userData.name) missingFields.push('Name');
                    if (!userData.email) missingFields.push('Email');
                    if (!userData.role) missingFields.push('Role');

                    // Create error message element
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'dynamic-error-message';
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.style.cssText =
                        'margin: 15px 0; padding: 12px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;';
                    errorDiv.innerHTML =
                        '<i class="fas fa-exclamation-triangle"></i> {{ __('Please fill in all required fields') }}: ' +
                        missingFields.join(', ');

                    // Insert error message at the top of the form
                    form.insertBefore(errorDiv, form.firstChild);

                    // Scroll to error message
                    errorDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                // Password confirmation check (only if password is provided)
                if (userData.password && userData.password !== userData.password_confirmation) {
                    e.preventDefault();

                    // Create error message element
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'dynamic-error-message';
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.style.cssText =
                        'margin: 15px 0; padding: 12px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;';
                    errorDiv.innerHTML =
                        '<i class="fas fa-exclamation-triangle"></i> {{ __('Password and confirmation do not match') }}';

                    // Insert error message at the top of the form
                    form.insertBefore(errorDiv, form.firstChild);

                    // Scroll to error message
                    errorDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Updating...') }}';
                submitBtn.disabled = true;
            });
        });

        function cancelForm() {
            window.location.href = '{{ route('dashboard.customer.index') }}';
        }

        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = [];

            if (password.length >= 8) score++;
            else feedback.push('{{ __('8 characters minimum') }}');

            if (/[a-z]/.test(password)) score++;
            else feedback.push('{{ __('lowercase letter') }}');

            if (/[A-Z]/.test(password)) score++;
            else feedback.push('{{ __('uppercase letter') }}');

            if (/[0-9]/.test(password)) score++;
            else feedback.push('{{ __('number') }}');

            if (/[^A-Za-z0-9]/.test(password)) score++;
            else feedback.push('{{ __('special character') }}');

            if (password.length >= 12) score++;

            if (score < 2) {
                return {
                    class: 'strength-weak',
                    text: '{{ __('Very Weak') }} - ' + feedback.join(', ')
                };
            } else if (score < 3) {
                return {
                    class: 'strength-weak',
                    text: '{{ __('Weak') }} - ' + feedback.join(', ')
                };
            } else if (score < 4) {
                return {
                    class: 'strength-medium',
                    text: '{{ __('Medium') }} - ' + feedback.join(', ')
                };
            } else if (score < 5) {
                return {
                    class: 'strength-strong',
                    text: '{{ __('Strong') }}'
                };
            } else {
                return {
                    class: 'strength-very-strong',
                    text: '{{ __('Very Strong') }}'
                };
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
    </script>
@endpush
