<!-- Header Section -->
<header class="header">
    @php
        $media = $store->getFirstMedia('logo');
    @endphp
    <div class="header-container">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">

                    @if ($store->getFirstMedia('store_logo'))
                        <img src="{{ route('store.image', $store->getFirstMedia('store_logo')->id) }}"
                            alt="{{ $store->name }}" class="logo-img">
                    @else
                        <i class="fas fa-store fa-2x"></i>
                    @endif
                    <div class="logo-text">
                        <h1>{{ $store->name }}</h1>
                    </div>
                </div>
            </div>

            <div class="header-center">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="ابحث عن الخدمات..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="header-actions">
                <div class="action-group">
                    <button class="action-btn notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
                @auth
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

                <div class="language-switcher">
                    <select class="form-select lang-select" onchange="if(this.value) window.location.href=this.value;">
                        <option value="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                            {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
                        <option value="{{ LaravelLocalization::getLocalizedURL('en') }}"
                            {{ app()->getLocale() === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                    </select>
                </div>
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
