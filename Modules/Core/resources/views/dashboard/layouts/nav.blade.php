<!-- Top Bar -->
<header class="dashboard-topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle mobile-only" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="breadcrumb">
            <i class="fas fa-home"></i>
            <span>{{ __('Dashboard') }}</span>
            @if (request()->routeIs('dashboard.category.*'))
                <i class="fas fa-chevron-left"></i>
                <span>{{ __('Sections') }}</span>
            @elseif(request()->routeIs('dashboard.product.*'))
                <i class="fas fa-chevron-left"></i>
                <span>{{ __('Products') }}</span>
            @elseif(request()->routeIs('dashboard.order.*'))
                <i class="fas fa-chevron-left"></i>
                <span>{{ __('Orders') }}</span>
            @endif
        </div>
    </div>

    <div class="topbar-right">
        <div class="search-box">
            <input type="text" placeholder="{{ __('Search in dashboard...') }}">
            <i class="fas fa-search"></i>
        </div>
        <!-- Language Switcher -->
        <div class="language-switcher d-flex gap-2">
            <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary' : 'btn-outline-secondary' }}">عربي</a>
            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">EN</a>
        </div>
        <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الوضع الليلي">
            <i class="fas fa-moon" id="theme-icon"></i>
        </button>

        <a href="{{ route('dashboard.notifications') }}" class="notifications" title="{{ __('الإشعارات') }}">
            <i class="fas fa-bell"></i>
            @php
                $unreadCount = Auth::user()->unreadNotifications()->count();
            @endphp
            @if ($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </a>

        <div class="user-menu">
            <div class="user-avatar">أ</div>
            <div class="user-info">
                <p class="user-name">{{ Auth::user()->name }}</p>
                <p class="user-role">مدير المتجر</p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>
