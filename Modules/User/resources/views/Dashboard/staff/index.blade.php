@extends('core::dashboard.layouts.app')

@section('title', __('Staffs List'))

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
            <h1 class="page-title">{{ __('إدارة الموظفين') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.staff.create') }}" class="add-staff-btn">
                    <i class="fas fa-plus"></i>
                    {{ __('إضافة موظف جديد') }}
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="staff-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="stat-number">{{ $users->count() }}</div>
                <div class="stat-label">{{ __('إجمالي الموظفين') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number">
                    {{ $users->filter(function ($user) use ($store) {return $user->stores->firstWhere('id', $store->id)?->pivot->is_active ?? false;})->count() }}
                </div>
                <div class="stat-label">{{ __('الموظفين المتفعيلين') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon verified">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-number">
                    {{ $users->filter(function ($user) use ($store) {return !($user->stores->firstWhere('id', $store->id)?->pivot->is_active ?? false);})->count() }}
                </div>
                <div class="stat-label">{{ __('الموظفين غير المتفعيلين') }}</div>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="staff-table-container">
            <div class="table-header">
                <h3>{{ __('قائمة الموظفين') }}</h3>
                <div class="table-actions">
                    <input type="text" id="staffs-search" placeholder="{{ __('البحث في الموظفين...') }}"
                        class="search-input">
                </div>
            </div>

            <div class="table-responsive">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>{{ __('الاسم') }}</th>
                            <th>{{ __('البريد الإلكتروني') }}</th>
                            <th>{{ __('الصورة') }}</th>
                            <th>{{ __('الحالة') }}</th>
                            <th>{{ __('تاريخ التسجيل') }}</th>
                            <th>{{ __('آخر دخول') }}</th>
                            <th>{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-wrapper">
                        @include('user::dashboard.staff.dataTables', [
                            'users' => $users,
                            'store' => $store,
                        ])
                    </tbody>
                </table>
            </div>

            <div class="pagination-container" id="users-pagination">
                @if ($users->hasPages())
                    {{ $users->links() }}
                @endif
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

        /* Staff Dashboard Styles - Dark Theme */
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

        .add-staff-btn {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
        }

        .add-staff-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(5, 150, 105, 0.3);
            color: white;
            text-decoration: none;
        }

        .staff-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #374151;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
        }

        .stat-icon.verified {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #d1d5db;
            font-weight: 500;
        }

        .staff-table-container {
            background: #374151;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #4b5563;
            background: #4b5563;
        }

        .table-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .table-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.75rem 1rem;
            border: 1px solid #6b7280;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 250px;
            background: #1f2937;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
        }

        .staff-table th {
            background: #4b5563;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            color: #ffffff;
            border-bottom: 1px solid #6b7280;
        }

        .staff-table td {
            padding: 1rem;
            border-bottom: 1px solid #4b5563;
            vertical-align: middle;
            color: #ffffff;
        }

        .staff-table tbody tr:hover {
            background: #4b5563;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .action-btn {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            min-width: 120px;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .action-btn i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .action-btn:hover i {
            transform: scale(1.1);
        }

        .action-btn.edit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .action-btn.edit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .action-btn.activate {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .action-btn.activate:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .action-btn.deactivate {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn.deactivate:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .action-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
        }

        .action-btn.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .btn-text {
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .staff-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: #ffffff;
        }

        .staff-name i {
            color: #d1d5db;
        }

        .email-text {
            color: #d1d5db;
            font-size: 0.9rem;
        }

        .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.verified {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.unverified {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .date-text {
            color: #d1d5db;
            font-size: 0.9rem;
        }

        .pagination-container {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            border-top: 1px solid #4b5563;
            background: #4b5563;
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

            .staff-stats {
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('staffs-search');
            let typingTimer;
            const typingDelay = 500; // نصف ثانية تأخير قبل البحث
            const usersTableWrapper = document.getElementById('users-table-wrapper');
            const usersPagination = document.getElementById('users-pagination');

            function fetchUsers(page = 1) {
                const search = searchInput.value;

                fetch(`{{ route('dashboard.staff.index') }}?search=${encodeURIComponent(search)}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        usersTableWrapper.innerHTML = data.html;
                        usersPagination.innerHTML = data.pagination;
                    })
                    .catch(err => console.error(err));
            }

            // البحث عند الكتابة
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchUsers();
                }, typingDelay);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            // التعامل مع الباجينيت
            usersPagination.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    e.preventDefault();
                    const url = new URL(e.target.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchUsers(page);
                }
            });
        });
    </script>

    <script>
        function toggleActivation(userId) {
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (!meta) {
                console.error('CSRF token not found!');
                return;
            }

            const token = meta.getAttribute('content');
            const btn = document.getElementById(`toggle-btn-${userId}`);
            const btnText = btn.querySelector('.btn-text');
            const icon = btn.querySelector('i');

            // للتشخيص
            console.log('Before toggle:', {
                userId: userId,
                btn: btn,
                btnText: btnText,
                icon: icon,
                currentClasses: btn.className,
                currentText: btnText ? btnText.textContent : 'btnText not found',
                currentIcon: icon ? icon.className : 'icon not found',
                dataIsActive: btn.getAttribute('data-is-active')
            });

            // التحقق من وجود العناصر
            if (!btnText) {
                console.error('btnText element not found!');
                return;
            }
            if (!icon) {
                console.error('icon element not found!');
                return;
            }

            // إضافة حالة التحميل
            btn.classList.add('loading');
            btn.disabled = true;

            // حفظ النص الأصلي
            const originalText = btnText.textContent;
            const originalIcon = icon.className;

            // تحديث النص أثناء التحميل
            btnText.textContent = 'جاري التحديث...';
            icon.className = 'fas fa-spinner fa-spin';

            fetch(`staff/${userId}/toggle-activation`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // للتشخيص
                    if (data.success) {
                        // إزالة حالة التحميل
                        btn.classList.remove('loading');
                        btn.disabled = false;

                        console.log('Current is_active:', data.is_active); // للتشخيص

                        // تحديث حالة الزر بطريقة بديلة وأكثر فعالية
                        if (data.is_active) {
                            // تحديث الزر بالكامل
                            btn.innerHTML = '<i class="fas fa-pause"></i><span class="btn-text">إلغاء التفعيل</span>';
                            btn.className = 'action-btn toggle-btn deactivate';
                            btn.setAttribute('title', 'إلغاء التفعيل');
                            btn.setAttribute('data-is-active', 'true');
                            console.log('Set to deactivate'); // للتشخيص
                        } else {
                            // تحديث الزر بالكامل
                            btn.innerHTML = '<i class="fas fa-play"></i><span class="btn-text">تفعيل</span>';
                            btn.className = 'action-btn toggle-btn activate';
                            btn.setAttribute('title', 'تفعيل');
                            btn.setAttribute('data-is-active', 'false');
                            console.log('Set to activate'); // للتشخيص
                        }

                        // للتشخيص بعد التحديث
                        console.log('After toggle:', {
                            userId: userId,
                            newClasses: btn.className,
                            newText: btn.querySelector('.btn-text')?.textContent,
                            newIcon: btn.querySelector('i')?.className,
                            newDataIsActive: btn.getAttribute('data-is-active')
                        });

                        // طريقة بديلة لتحديث النص في حالة عدم عمل الطريقة الأولى
                        setTimeout(() => {
                            const btnTextCheck = btn.querySelector('.btn-text');
                            if (btnTextCheck && btnTextCheck.textContent !== (data.is_active ? 'إلغاء التفعيل' :
                                    'تفعيل')) {
                                console.log('Text not updated, trying alternative method');
                                btnTextCheck.textContent = data.is_active ? 'إلغاء التفعيل' : 'تفعيل';
                            }
                        }, 100);

                        // تحديث عمود الحالة في الجدول
                        const row = btn.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(4)'); // عمود الحالة
                        if (statusCell) {
                            if (data.is_active) {
                                statusCell.innerHTML = '<span class="status-badge verified">متفعل</span>';
                            } else {
                                statusCell.innerHTML = '<span class="status-badge unverified">غير متفعل</span>';
                            }
                        }

                        // إظهار رسالة النجاح
                        showNotification('success', 'تم التحديث بنجاح', data.message);

                    } else {
                        throw new Error(data.message || 'حدث خطأ غير متوقع');
                    }
                })
                .catch(error => {
                    // إزالة حالة التحميل في حالة الخطأ
                    btn.classList.remove('loading');
                    btn.disabled = false;

                    // استعادة النص الأصلي
                    btnText.textContent = originalText;
                    icon.className = originalIcon;

                    console.error('Error:', error);
                    showNotification('error', 'خطأ في التحديث', error.message || 'حدث خطأ أثناء تحديث حالة الموظف');
                });
        }

        // دالة لإظهار الإشعارات
        function showNotification(type, title, message) {
            const notificationContainer = document.querySelector('.notification-container');
            if (!notificationContainer) return;

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
                    <i class="${iconMap[type] || iconMap.info}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            `;

            notificationContainer.appendChild(notification);

            // إزالة الإشعار تلقائياً بعد 5 ثوان
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 400);
            }, 5000);

            // تحريك شريط التقدم
            setTimeout(() => {
                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 5000ms linear';
                }
            }, 100);
        }
    </script>
@endpush
