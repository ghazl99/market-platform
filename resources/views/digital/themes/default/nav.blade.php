<style>
    .language-switcher {
        display: inline-flex;
        background: var(--bg-primary, #fff);
        border: 1px solid var(--border-color, #ddd);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .lang-btn {
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: var(--text-color, #101010);
        transition: all 0.2s ease;
        background: transparent;
    }

    .lang-btn:hover {
        background: var(--hover-bg, #f0f0f0);
    }

    .lang-btn.active {
        background: var(--primary-color, #007bff);
        color: #fff;
    }

    .lang-btn:first-child {
        border-right: 1px solid var(--border-color, #ddd);
    }

    /* دعم RTL */
    [dir="rtl"] .lang-btn:first-child {
        border-right: none;
        border-left: 1px solid var(--border-color, #ddd);
    }
</style>
<!-- Header Section -->
<header class="header">
    @php
        $media = current_store()->getFirstMedia('logo');
    @endphp
    <div class="header-container">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">
                    @if ($media)
                        <img src="{{ route('store.image', $media->id) }}" alt="{{ current_store()->name }}" class="logo-img">
                    @else
                        <i class="fas fa-store fa-2x"></i>
                    @endif
                    <div class="logo-text">
                        <h1>{{ current_store()->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="header-center">
                <div class="search-bar">
                    {{-- <i class="fas fa-search search-icon"></i> --}}
                    <input type="text" placeholder="ابحث عن الخدمات..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="header-actions">

                @auth
                    <div class="action-group">
                        <a class="action-btn notification-btn" href="{{ Route('notification.index') }}"
                            style="text-decoration: none">
                            <i class="fas fa-bell"></i>
                            @php
                                $unreadCount = auth()->user()->unreadNotifications->count();
                            @endphp

                            @if ($unreadCount > 0)
                                <span class="notification-badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </div>
                    <a href="{{ route('auth.profile.edit', Auth::user()->id) }}" class="user-profile-link"
                        style="text-decoration: none;">
                        <div class="user-profile-mini">
                            <img src="{{ Auth::user()->profilePhotoUrl }}" alt="{{ Auth::user()->name }}"
                                class="user-avatar-mini">
                            <div class="user-info-mini">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </a>
                @else
                    <a href="{{ route('auth.customer.login') }}" class="btn btn-success" title="{{ __('Login') }}">
                        تسجيل الدخول
                    </a>
                @endauth

                <div class="language-switcher"> <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                        class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"> العربية </a> <a
                        href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                        class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"> English </a> </div>
                <div class="header-controls">
                    <button class="theme-toggle" id="themeToggle" title="تبديل الوضع">
                        <i class="fas fa-moon"></i>
                    </button>
                    <button class="menu-toggle" id="menuToggle" title="القائمة">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>

            </div>
        </div>

        <!-- Header Progress Bar -->
        <div class="header-progress">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>
</header>
