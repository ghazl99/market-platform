<!-- Sidebar -->
<aside class="dashboard-sidebar" id="sidebar">
    @php
        $media = $store->getFirstMedia('logo');
    @endphp
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ route('store.image', $media->id) }}" alt="{{ $store->name }}">
            <div class="sidebar-logo-text">
                <h2>{{ __('Dashboard') }}</h2>
                <p>{{ $store->name }}</p>
            </div>
        </div>
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">{{ __('Dashboard') }}</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="nav-item-text">{{ __('Dashboard') }}</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span class="nav-item-text">الإحصائيات</span>
            </a>
            <a href="{{ route('dashboard.order.index') }}" class="nav-item {{ request()->routeIs('dashboard.order.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-item-text">{{ __('Orders') }}</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">إدارة المنتجات</div>
            <a href="{{ route('dashboard.product.index') }}" class="nav-item {{ request()->routeIs('dashboard.product.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span class="nav-item-text">{{ __('Products') }}</span>
            </a>
            <a href="{{ route('dashboard.category.index') }}"
                class="nav-item {{ request()->routeIs('dashboard.category.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="nav-item-text">{{ __('Categories') }}</span>
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
            <a href="#" class="nav-item">
                <i class="fas fa-percentage"></i>
                <span class="nav-item-text">الخصومات</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">العملاء</div>
            <a href="users.html" class="nav-item">
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
            <div class="nav-section-title">الإعدادات</div>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span class="nav-item-text">عام</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-shield-alt"></i>
                <span class="nav-item-text">الأمان</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-item-text">تسجيل الخروج</span>
            </a>
        </div>
    </nav>
</aside>
