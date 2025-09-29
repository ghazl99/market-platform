@extends('core::store.layouts.app')
@section('title', __('Profile'))
@push('styles')
    <style>
        /* Profile Page Styles */
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .profile-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
        }

        .profile-sidebar {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .profile-main {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 2rem;
        }

        .avatar-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .avatar-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
        }

        .avatar-upload {
            position: relative;
            display: inline-block;
        }

        .avatar-upload input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .avatar-upload-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .avatar-upload-btn:hover {
            background: var(--primary-dark);
        }

        .profile-info {
            text-align: center;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .profile-email {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .sidebar-menu a.active {
            background: var(--primary-color);
            color: white;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-primary);
            background: var(--bg-secondary);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-danger {
            background: var(--error-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .setting-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .setting-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .setting-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .setting-description {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--border-color);
            transition: 0.3s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: var(--primary-color);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(26px);
        }

        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        .security-info h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
        }

        .security-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .security-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: var(--success-color);
            color: white;
        }

        .status-inactive {
            background: var(--text-light);
            color: var(--text-primary);
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .activity-icon.login {
            background: var(--success-color);
            color: white;
        }

        .activity-icon.payment {
            background: var(--primary-color);
            color: white;
        }

        .activity-icon.security {
            background: var(--warning-color);
            color: white;
        }

        .activity-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .activity-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .activity-time {
            margin-right: auto;
            color: var(--text-light);
            font-size: 0.8rem;
        }

        /* API Section Styles */
        .api-status-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .api-status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .api-status-header h3 {
            margin: 0;
            color: var(--text-primary);
        }

        .api-token-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .api-token-card h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
        }

        .token-display {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .token-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .token-warning {
            color: var(--warning-color);
            font-size: 0.9rem;
            margin: 0;
        }

        .api-endpoints {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .api-endpoints h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
        }

        .endpoint-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .endpoint-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 1rem;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .endpoint-method {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }

        .endpoint-method.get {
            background: var(--success-color);
            color: white;
        }

        .endpoint-method.post {
            background: var(--primary-color);
            color: white;
        }

        .endpoint-url {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .endpoint-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .api-stats {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .api-stats h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-info {
            flex: 1;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .api-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1rem;
            }

            .profile-layout {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .profile-sidebar {
                order: 1;
                margin-bottom: 1rem;
                position: static;
                height: auto;
                max-height: none;
            }

            .profile-main {
                order: 2;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }

            .endpoint-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .endpoint-method {
                justify-self: start;
            }

            .api-actions {
                flex-direction: column;
            }

            .token-display {
                flex-direction: column;
            }


            .profile-stats {
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .stat-item {
                padding: 0.75rem;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .sidebar-menu a {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .sidebar-menu a i {
                width: 16px;
            }

            .avatar-image {
                width: 80px;
                height: 80px;
            }

            .profile-name {
                font-size: 1.2rem;
            }

            .profile-email {
                font-size: 0.9rem;
            }

            .api-status-card,
            .api-token-card,
            .api-endpoints,
            .api-stats {
                padding: 1rem;
            }

            .endpoint-item {
                padding: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .profile-layout {
                display: flex;
                flex-direction: column;
            }

            .profile-sidebar {
                width: 100%;
                margin-bottom: 1rem;
            }

            .profile-main {
                width: 100%;
            }
        }
    </style>
@endpush
@section('content')
    <div class="profile-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">الملف الشخصي والإعدادات</h1>
            <p class="page-subtitle">إدارة معلوماتك الشخصية وإعدادات الحساب</p>
        </div>

        <div class="profile-layout">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-upload">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=120&h=120&fit=crop&crop=face"
                            alt="Profile" class="avatar-image" id="avatarImage">
                        <input type="file" id="avatarInput" accept="image/*">
                        <button class="avatar-upload-btn" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera"></i> تغيير الصورة
                        </button>
                    </div>
                </div>

                <div class="profile-info">
                    <h3 class="profile-name" id="profileName">أحمد محمد</h3>
                    <p class="profile-email" id="profileEmail">ahmed@example.com</p>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="totalOrders">24</div>
                        <div class="stat-label">الطلبات</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="totalSpent">$1,250</div>
                        <div class="stat-label">إجمالي الإنفاق</div>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <li><a href="#personal" class="menu-link active" data-section="personal">
                            <i class="fas fa-user"></i> المعلومات الشخصية
                        </a></li>
                    <li><a href="#security" class="menu-link" data-section="security">
                            <i class="fas fa-shield-alt"></i> الأمان
                        </a></li>
                    <li><a href="#notifications" class="menu-link" data-section="notifications">
                            <i class="fas fa-bell"></i> الإشعارات
                        </a></li>
                    <li><a href="#privacy" class="menu-link" data-section="privacy">
                            <i class="fas fa-lock"></i> الخصوصية
                        </a></li>
                    <li><a href="#activity" class="menu-link" data-section="activity">
                            <i class="fas fa-history"></i> النشاط
                        </a></li>
                    <li><a href="#api" class="menu-link" data-section="api">
                            <i class="fas fa-code"></i> API & التوكن
                        </a></li>
                </ul>
            </div>

            <!-- Profile Main Content -->
            <div class="profile-main">
                <!-- Personal Information Section -->
                <div class="content-section active" id="personalSection">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        المعلومات الشخصية
                    </h2>

                    <form id="personalForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">الاسم الأول</label>
                                <input type="text" class="form-input" id="firstName" value="أحمد">
                            </div>
                            <div class="form-group">
                                <label class="form-label">الاسم الأخير</label>
                                <input type="text" class="form-input" id="lastName" value="محمد">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-input" id="email" value="ahmed@example.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-input" id="phone" value="+966501234567">
                        </div>

                        <div class="form-group">
                            <label class="form-label">البلد</label>
                            <select class="form-input form-select" id="country">
                                <option value="SA">السعودية</option>
                                <option value="AE">الإمارات</option>
                                <option value="KW">الكويت</option>
                                <option value="QA">قطر</option>
                                <option value="BH">البحرين</option>
                                <option value="OM">عمان</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">المدينة</label>
                            <input type="text" class="form-input" id="city" value="الرياض">
                        </div>

                        <div class="form-group">
                            <label class="form-label">العنوان</label>
                            <textarea class="form-input form-textarea" id="address" placeholder="أدخل عنوانك الكامل">شارع الملك فهد، حي النخيل، الرياض 12345</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </form>
                </div>

                <!-- Security Section -->
                <div class="content-section" id="securitySection">
                    <h2 class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        الأمان
                    </h2>

                    <div class="security-item">
                        <div class="security-info">
                            <h4>كلمة المرور</h4>
                            <p>آخر تحديث: منذ 3 أشهر</p>
                        </div>
                        <div class="security-status">
                            <span class="status-badge status-active">آمن</span>
                            <button class="btn btn-secondary">تغيير</button>
                        </div>
                    </div>

                    <div class="security-item">
                        <div class="security-info">
                            <h4>المصادقة الثنائية</h4>
                            <p>إضافة طبقة حماية إضافية لحسابك</p>
                        </div>
                        <div class="security-status">
                            <span class="status-badge status-inactive">غير مفعل</span>
                            <button class="btn btn-primary">تفعيل</button>
                        </div>
                    </div>

                    <div class="security-item">
                        <div class="security-info">
                            <h4>جلسات نشطة</h4>
                            <p>إدارة الأجهزة المتصلة بحسابك</p>
                        </div>
                        <div class="security-status">
                            <span class="status-badge status-active">3 أجهزة</span>
                            <button class="btn btn-secondary">إدارة</button>
                        </div>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div class="content-section" id="notificationsSection">
                    <h2 class="section-title">
                        <i class="fas fa-bell"></i>
                        الإشعارات
                    </h2>

                    <div class="settings-grid">
                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-envelope"></i>
                                الإشعارات عبر البريد الإلكتروني
                            </h3>
                            <p class="setting-description">تلقي إشعارات حول الطلبات والمدفوعات</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-mobile-alt"></i>
                                الإشعارات النصية
                            </h3>
                            <p class="setting-description">تلقي رسائل نصية للتنبيهات المهمة</p>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-shopping-cart"></i>
                                تحديثات الطلبات
                            </h3>
                            <p class="setting-description">إشعارات حول حالة الطلبات</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-wallet"></i>
                                تحديثات المحفظة
                            </h3>
                            <p class="setting-description">إشعارات حول المعاملات المالية</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Section -->
                <div class="content-section" id="privacySection">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i>
                        الخصوصية
                    </h2>

                    <div class="settings-grid">
                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-eye"></i>
                                الملف الشخصي العام
                            </h3>
                            <p class="setting-description">إظهار معلوماتك للآخرين</p>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-search"></i>
                                السماح بالبحث
                            </h3>
                            <p class="setting-description">السماح للآخرين بالعثور عليك</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-chart-line"></i>
                                تحليلات الاستخدام
                            </h3>
                            <p class="setting-description">مشاركة بيانات الاستخدام لتحسين الخدمة</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-card">
                            <h3 class="setting-title">
                                <i class="fas fa-cookie-bite"></i>
                                ملفات تعريف الارتباط
                            </h3>
                            <p class="setting-description">استخدام ملفات تعريف الارتباط</p>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Activity Section -->
                <div class="content-section" id="activitySection">
                    <h2 class="section-title">
                        <i class="fas fa-history"></i>
                        النشاط الأخير
                    </h2>

                    <div class="activity-item">
                        <div class="activity-icon login">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="activity-content">
                            <h4>تسجيل دخول</h4>
                            <p>تم تسجيل الدخول بنجاح</p>
                        </div>
                        <div class="activity-time">منذ 5 دقائق</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon payment">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <h4>دفع ناجح</h4>
                            <p>تم دفع $25.00 لطلب #12345</p>
                        </div>
                        <div class="activity-time">منذ ساعة</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon security">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="activity-content">
                            <h4>تغيير كلمة المرور</h4>
                            <p>تم تحديث كلمة المرور بنجاح</p>
                        </div>
                        <div class="activity-time">منذ يومين</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon payment">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="activity-content">
                            <h4>إضافة رصيد</h4>
                            <p>تم إضافة $100.00 إلى المحفظة</p>
                        </div>
                        <div class="activity-time">منذ 3 أيام</div>
                    </div>
                </div>

                <!-- API & Token Section -->
                <div class="content-section" id="apiSection">
                    <h2 class="section-title">
                        <i class="fas fa-code"></i>
                        API & التوكن
                    </h2>

                    <!-- API Status -->
                    <div class="api-status-card">
                        <div class="api-status-header">
                            <h3>حالة API</h3>
                            <span class="status-badge status-active">نشط</span>
                        </div>
                        <p>API الخاص بك يعمل بشكل طبيعي</p>
                    </div>

                    <!-- API Token -->
                    <div class="api-token-card">
                        <h3>API Token</h3>
                        <div class="token-display">
                            <input type="password" class="token-input" id="apiToken"
                                value="sk_live_51H1234567890abcdef" readonly>
                            <button class="btn btn-secondary" id="toggleToken">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-primary" id="copyToken">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="token-warning">⚠️ لا تشارك هذا التوكن مع أي شخص</p>
                    </div>

                    <!-- API Endpoints -->
                    <div class="api-endpoints">
                        <h3>API Endpoints</h3>
                        <div class="endpoint-list">
                            <div class="endpoint-item">
                                <div class="endpoint-method get">GET</div>
                                <div class="endpoint-url">/api/v1/products</div>
                                <div class="endpoint-desc">جلب قائمة المنتجات</div>
                            </div>
                            <div class="endpoint-item">
                                <div class="endpoint-method post">POST</div>
                                <div class="endpoint-url">/api/v1/orders</div>
                                <div class="endpoint-desc">إنشاء طلب جديد</div>
                            </div>
                            <div class="endpoint-item">
                                <div class="endpoint-method get">GET</div>
                                <div class="endpoint-url">/api/v1/orders/{id}</div>
                                <div class="endpoint-desc">جلب تفاصيل الطلب</div>
                            </div>
                            <div class="endpoint-item">
                                <div class="endpoint-method get">GET</div>
                                <div class="endpoint-url">/api/v1/wallet/balance</div>
                                <div class="endpoint-desc">جلب رصيد المحفظة</div>
                            </div>
                        </div>
                    </div>

                    <!-- API Usage Stats -->
                    <div class="api-stats">
                        <h3>إحصائيات الاستخدام</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value">1,250</div>
                                    <div class="stat-label">طلبات API</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value">45ms</div>
                                    <div class="stat-label">متوسط الاستجابة</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-value">99.9%</div>
                                    <div class="stat-label">معدل النجاح</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- API Actions -->
                    <div class="api-actions">
                        <button class="btn btn-primary" id="regenerateToken">
                            <i class="fas fa-sync"></i>
                            إعادة توليد التوكن
                        </button>
                        <button class="btn btn-secondary" id="downloadDocs">
                            <i class="fas fa-download"></i>
                            تحميل الوثائق
                        </button>
                        <button class="btn btn-danger" id="revokeToken">
                            <i class="fas fa-ban"></i>
                            إلغاء التوكن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
