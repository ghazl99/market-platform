@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="page-header">
            <h1 class="page-title">مرحباً بك في لوحة التحكم</h1>
            <p class="page-subtitle">إليك نظرة عامة على أداء متجرك اليوم</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">إجمالي المبيعات</h3>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">$24,580</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12.5%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">الطلبات الجديدة</h3>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">1,247</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8.2%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">العملاء الجدد</h3>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">892</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15.3%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">معدل التحويل</h3>
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                <div class="stat-value">3.24%</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-2.1%</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">مبيعات الشهر</h3>
                    <div class="chart-actions">
                        <button class="chart-btn active">7 أيام</button>
                        <button class="chart-btn">30 يوم</button>
                        <button class="chart-btn">90 يوم</button>
                    </div>
                </div>
                <div
                    style="height: 300px; background: var(--bg-secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                    <i class="fas fa-chart-line" style="font-size: 3rem;"></i>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">الأنشطة الأخيرة</h3>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon order">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="activity-content">
                            <h4 class="activity-title">طلب جديد #1234</h4>
                            <p class="activity-desc">تم استلام طلب بقيمة $150</p>
                        </div>
                        <div class="activity-time">منذ 5 دقائق</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon payment">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <h4 class="activity-title">دفعة مستلمة</h4>
                            <p class="activity-desc">دفعة بقيمة $89.99</p>
                        </div>
                        <div class="activity-time">منذ 15 دقيقة</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon user">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4 class="activity-title">عميل جديد</h4>
                            <p class="activity-desc">سارة أحمد سجلت حساب جديد</p>
                        </div>
                        <div class="activity-time">منذ 30 دقيقة</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon order">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="activity-content">
                            <h4 class="activity-title">تحديث المخزون</h4>
                            <p class="activity-desc">تم إضافة 50 منتج جديد</p>
                        </div>
                        <div class="activity-time">منذ ساعة</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
