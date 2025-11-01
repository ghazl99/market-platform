<!-- Sidebar -->
@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    // دالة مساعدة لتحديد الصفحة النشطة
    function isActive($patterns)
    {
        $currentPath = request()->path();
        foreach ($patterns as $pattern) {
            if (str_contains($currentPath, $pattern)) {
                return true;
            }
        }
        return false;
    }
@endphp

<aside class="dashboard-sidebar" id="sidebar">

    <div class="sidebar-header">
        <div class="sidebar-logo">
            @if (!empty($store) && !empty($media))
                @php
                    $media = $store->getFirstMedia('logo');
                @endphp
                @if ($media)
                    <img src="{{ route('store.image', $media->id) }}" alt="{{ $store->name }}">
                @else
                    <i class="fas fa-store fa-2x"></i>
                @endif
                <div class="sidebar-logo-text">
                    <h2>{{ __('Dashboard') }}</h2>
                    <p>{{ $store->name }}</p>
                </div>
            @else
                <i class="fas fa-store fa-2x text-primary"></i>
                <div class="sidebar-logo-text">
                    <h2>{{ __('Dashboard') }}</h2>
                    <p>{{ __('Market Platform') }}</p>
                </div>
            @endif
        </div>
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>


    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">{{ __('Dashboard') }}</div>
            @php
                $currentPath = trim(request()->path(), '/');
                $isDashboardActive =
                    $currentPath === 'dashboard' || $currentPath === 'ar/dashboard' || $currentPath === 'en/dashboard';
            @endphp
            <a href="{{ LaravelLocalization::localizeURL('dashboard') }}"
                class="nav-item {{ $isDashboardActive ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="nav-item-text">{{ __('Dashboard') }}</span>
            </a>
            <a href="{{ route('dashboard.statistics') }}"
                class="nav-item {{ isActive(['dashboard/statistics']) ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="nav-item-text">الإحصائيات</span>
            </a>
            <a href="{{ route('dashboard.notifications') }}"
                class="nav-item {{ isActive(['dashboard/notifications']) ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="nav-item-text">{{ __('الإشعارات') }}</span>
                @php
                    $unreadCount = Auth::user()->unreadNotifications()->count();
                @endphp
                @if ($unreadCount > 0)
                    <span class="nav-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ LaravelLocalization::localizeURL('dashboard/orders') }}"
                class="nav-item {{ isActive(['dashboard/orders']) ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-item-text">{{ __('Orders') }}</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">إدارة المنتجات</div>
            <a href="{{ LaravelLocalization::localizeURL('dashboard/products') }}"
                class="nav-item {{ isActive(['dashboard/products']) ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span class="nav-item-text">{{ __('Products') }}</span>
            </a>
            <a href="{{ LaravelLocalization::localizeURL('dashboard/categories') }}"
                class="nav-item {{ isActive(['dashboard/categories']) ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span class="nav-item-text">{{ __('Sections') }}</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-warehouse"></i>
                <span class="nav-item-text">المخزون</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">المبيعات</div>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span class="nav-item-text">التقارير</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-credit-card"></i>
                <span class="nav-item-text">المدفوعات</span>
            </a>
            <a href="{{ route('dashboard.dashboard.payment-methods.index') }}"
                class="nav-item {{ isActive(['dashboard/dashboard/payment-methods']) ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span class="nav-item-text">طرق الدفع</span>
            </a>
            <a href="{{ route('dashboard.dashboard.payment-requests.index') }}"
                class="nav-item {{ isActive(['dashboard/dashboard/payment-requests']) ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span class="nav-item-text">طلبات الدفع</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-percentage"></i>
                <span class="nav-item-text">الخصومات</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">العملاء</div>
            <a href="{{ LaravelLocalization::localizeURL('dashboard/customer') }}"
                class="nav-item {{ isActive(['dashboard/customer']) ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="nav-item-text">العملاء</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-comments"></i>
                <span class="nav-item-text">التقييمات</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-headset"></i>
                <span class="nav-item-text">الدعم</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">الإدارة</div>
            <a href="{{ route('dashboard.staff.index') }}"
                class="nav-item {{ isActive(['dashboard/staff']) ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span class="nav-item-text">الموظفين</span>
            </a>
            <a href="{{ route('admin.groups.index') }}"
                class="nav-item {{ isActive(['admin/groups']) ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                <span class="nav-item-text">{{ __('Groups') }}</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">الإعدادات</div>
            @if (!empty($store))
                <a href="{{ route('store.settings.edit', $store->id) }}"
                    class="nav-item {{ isActive(['store/settings']) ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="nav-item-text">{{ __('Store Settings') }}</span>
                </a>
            @endif
            <a href="{{ LaravelLocalization::localizeURL('dashboard/permissions') }}"
                class="nav-item {{ isActive(['dashboard/permissions']) ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span class="nav-item-text">{{ __('Permissions') }}</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-lock"></i>
                <span class="nav-item-text">{{ __('Security') }}</span>
            </a>

            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="#" class="nav-item"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-item-text">تسجيل الخروج</span>
            </a>

        </div>
    </nav>
</aside>
