@extends('core::dashboard.layouts.app')

@section('title', __('Customer Details'))

@push('styles')
    <style>
        .customer-show-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color, #e5e7eb);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 12px;
        }

        .back-btn,
        .edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .back-btn {
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-secondary, #6b7280);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .back-btn:hover {
            background: var(--bg-tertiary, #f3f4f6);
            color: var(--text-primary, #374151);
        }

        .edit-btn {
            background: var(--primary-color, #3b82f6);
            color: white;
        }

        .edit-btn:hover {
            background: var(--primary-hover, #2563eb);
            transform: translateY(-1px);
        }

        .customer-profile {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-card {
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--secondary-color, #8b5cf6));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 700;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin-bottom: 8px;
        }

        .profile-email {
            font-size: 16px;
            color: var(--text-secondary, #6b7280);
            margin-bottom: 20px;
        }

        .profile-role {
            display: inline-block;
            padding: 6px 16px;
            background: var(--success-color, #10b981);
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .profile-role.customer {
            background: var(--info-color, #06b6d4);
        }

        .profile-role.staff {
            background: var(--warning-color, #f59e0b);
        }

        .profile-role.owner {
            background: var(--danger-color, #ef4444);
        }

        .profile-status {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
        }

        .profile-status.active {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .profile-status.inactive {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .profile-status.pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .detail-card {
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary-color, #3b82f6);
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-light, #f3f4f6);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--text-secondary, #6b7280);
            font-size: 14px;
        }

        .detail-value {
            font-weight: 500;
            color: var(--text-primary, #1f2937);
            text-align: right;
            max-width: 200px;
            word-break: break-word;
        }

        .detail-value.empty {
            color: var(--text-muted, #9ca3af);
            font-style: italic;
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-info i {
            color: var(--primary-color, #3b82f6);
            width: 16px;
        }

        .notification-preferences {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .preference-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
        }

        .preference-item i {
            width: 16px;
            text-align: center;
        }

        .preference-item.enabled i {
            color: var(--success-color, #10b981);
        }

        .preference-item.disabled i {
            color: var(--text-muted, #9ca3af);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
            color: white;
        }

        .stat-icon.orders {
            background: var(--primary-color, #3b82f6);
        }

        .stat-icon.spent {
            background: var(--success-color, #10b981);
        }

        .stat-icon.last-order {
            background: var(--warning-color, #f59e0b);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary, #6b7280);
        }

        @media (max-width: 768px) {
            .customer-profile {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                gap: 20px;
                align-items: stretch;
            }

            .page-actions {
                justify-content: center;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="customer-show-container">
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
                <div class="notification-progress"></div>
                <button class="notification-close" onclick="this.parentElement.classList.remove('show')">
                    <i class="fas fa-times"></i>
                </button>
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
                <div class="notification-progress"></div>
                <button class="notification-close" onclick="this.parentElement.classList.remove('show')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Customer Details') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.customer.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Customers') }}
                </a>
                <a href="{{ route('dashboard.customer.edit', $customer->id) }}" class="edit-btn">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Customer') }}
                </a>
            </div>
        </div>

        <!-- Customer Profile -->
        <div class="customer-profile">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-avatar">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="profile-name">{{ $customer->name }}</div>
                <div class="profile-email">{{ $customer->email }}</div>
                <div class="profile-role {{ $customer->getRoleNames()->first() }}">
                    {{ __('Customer') }}
                </div>
                <div class="profile-status {{ $customer->email_verified_at ? 'active' : 'pending' }}">
                    <i class="fas fa-circle"></i>
                    {{ $customer->email_verified_at ? __('Active') : __('Pending') }}
                </div>
            </div>

            <!-- Contact Information -->
            <div class="detail-card">
                <h3 class="card-title">
                    <i class="fas fa-phone"></i>
                    {{ __('Contact Information') }}
                </h3>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Phone Number') }}</span>
                    <div class="detail-value {{ $customer->phone ? '' : 'empty' }}">
                        @if ($customer->phone)
                            <div class="contact-info">
                                <i class="fas fa-phone"></i>
                                {{ $customer->phone }}
                            </div>
                        @else
                            {{ __('Not provided') }}
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Email Address') }}</span>
                    <div class="detail-value">
                        <div class="contact-info">
                            <i class="fas fa-envelope"></i>
                            {{ $customer->email }}
                        </div>
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Birth Date') }}</span>
                    <div class="detail-value {{ $customer->birth_date ? '' : 'empty' }}">
                        @if ($customer->birth_date)
                            {{ $customer->birth_date->format('Y-m-d') }}
                        @else
                            {{ __('Not provided') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">
            <!-- Address Information -->
            <div class="detail-card">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ __('Address Information') }}
                </h3>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Address') }}</span>
                    <div class="detail-value {{ $customer->address ? '' : 'empty' }}">
                        {{ $customer->address ?: __('Not provided') }}
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('City') }}</span>
                    <div class="detail-value {{ $customer->city ? '' : 'empty' }}">
                        {{ $customer->city ?: __('Not provided') }}
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Postal Code') }}</span>
                    <div class="detail-value {{ $customer->postal_code ? '' : 'empty' }}">
                        {{ $customer->postal_code ?: __('Not provided') }}
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Country') }}</span>
                    <div class="detail-value {{ $customer->country ? '' : 'empty' }}">
                        @if ($customer->country)
                            @switch($customer->country)
                                @case('SA')
                                    {{ __('Saudi Arabia') }}
                                @break

                                @case('AE')
                                    {{ __('UAE') }}
                                @break

                                @case('KW')
                                    {{ __('Kuwait') }}
                                @break

                                @case('QA')
                                    {{ __('Qatar') }}
                                @break

                                @case('BH')
                                    {{ __('Bahrain') }}
                                @break

                                @case('OM')
                                    {{ __('Oman') }}
                                @break

                                @default
                                    {{ $customer->country }}
                            @endswitch
                        @else
                            {{ __('Not provided') }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="detail-card">
                <h3 class="card-title">
                    <i class="fas fa-cog"></i>
                    {{ __('Preferences') }}
                </h3>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Language') }}</span>
                    <div class="detail-value {{ $customer->language ? '' : 'empty' }}">
                        @if ($customer->language)
                            {{ $customer->language == 'ar' ? __('Arabic') : __('English') }}
                        @else
                            {{ __('Not set') }}
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Timezone') }}</span>
                    <div class="detail-value {{ $customer->timezone ? '' : 'empty' }}">
                        @if ($customer->timezone)
                            @switch($customer->timezone)
                                @case('Asia/Riyadh')
                                    {{ __('Riyadh (GMT+3)') }}
                                @break

                                @case('Asia/Dubai')
                                    {{ __('Dubai (GMT+4)') }}
                                @break

                                @case('Asia/Kuwait')
                                    {{ __('Kuwait (GMT+3)') }}
                                @break

                                @default
                                    {{ $customer->timezone }}
                            @endswitch
                        @else
                            {{ __('Not set') }}
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Notifications') }}</span>
                    <div class="detail-value">
                        <div class="notification-preferences">
                            <div class="preference-item {{ $customer->email_notifications ? 'enabled' : 'disabled' }}">
                                <i class="fas {{ $customer->email_notifications ? 'fa-check' : 'fa-times' }}"></i>
                                {{ __('Email Notifications') }}
                            </div>
                            <div class="preference-item {{ $customer->sms_notifications ? 'enabled' : 'disabled' }}">
                                <i class="fas {{ $customer->sms_notifications ? 'fa-check' : 'fa-times' }}"></i>
                                {{ __('SMS Notifications') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="detail-card">
                <h3 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    {{ __('Account Information') }}
                </h3>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Role') }}</span>
                    <div class="detail-value">
                        <span class="profile-role {{ $customer->getRoleNames()->first() }}">
                            {{ __('Customer') }}
                        </span>
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Status') }}</span>
                    <div class="detail-value">
                        <span class="profile-status {{ $customer->email_verified_at ? 'active' : 'pending' }}">
                            <i class="fas fa-circle"></i>
                            {{ $customer->email_verified_at ? __('Active') : __('Pending') }}
                        </span>
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Member Since') }}</span>
                    <div class="detail-value">
                        {{ $customer->created_at->format('Y-m-d') }}
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ __('Last Login') }}</span>
                    <div class="detail-value {{ $customer->last_login_at ? '' : 'empty' }}">
                        @if ($customer->last_login_at)
                            {{ $customer->last_login_at->format('Y-m-d H:i') }}
                        @else
                            {{ __('Never') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics (Placeholder for future features) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon orders">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">{{ __('Total Orders') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon spent">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">{{ __('Total Spent') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon last-order">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">-</div>
                <div class="stat-label">{{ __('Last Order') }}</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Professional Notifications System
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide notifications after 5 seconds
            const notifications = document.querySelectorAll('.professional-notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            });

            // Close button functionality
            document.querySelectorAll('.notification-close').forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.classList.remove('show');
                });
            });
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
        .professional-notification {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .professional-notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification-progress {
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    `;
        document.head.appendChild(style);
    </script>
@endpush
