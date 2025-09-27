<nav class="navbar">
    <div class="nav-container">
        <!-- Logo -->
        <div class="nav-logo">
            <div class="logo-icon"><i class="fas fa-store"></i></div>
            <div class="logo-text">
                <span class="logo-name">{{ __('Market Platform') }}</span>
                <span class="logo-tagline">{{ __('Smart Commerce Platform') }}</span>
            </div>
        </div>

        <!-- Menu -->
        <div class="nav-menu">
            <a href="#home" class="nav-link"><i class="fas fa-home"></i> <span>{{ __('Home') }}</span></a>
            <a href="#services" class="nav-link"><i class="fas fa-cogs"></i> <span>{{ __('Services') }}</span></a>
            <a href="#demo" class="nav-link"><i class="fas fa-play"></i> <span>{{ __('Demo') }}</span></a>

            <a href="#pricing" class="nav-link"><i class="fas fa-tags"></i> <span>{{ __('Pricing') }}</span></a>
        </div>

        <!-- Actions -->
        <div class="nav-actions">
            <div class="language-switcher d-flex gap-2">
                <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                    class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary' : 'btn-outline-secondary' }}">عربي</a>
                <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                    class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">EN</a>
            </div>

            @guest
                <a href="{{ route('auth.login') }}" class="btn btn-outline" title="{{ __('Login') }}">
                    <i class="fas fa-sign-in-alt"></i> <span>{{ __('Login') }}</span>
                </a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary" title="{{ __('Register') }}">
                    <i class="fas fa-user-plus"></i> <span>{{ __('Register') }}</span>
                </a>
            @else
                <a href="#" class="btn btn-outline-primary me-2 d-flex align-items-center">
                    <i class="fas fa-user me-1"></i> <span>{{ Auth::user()->name }}</span>
                </a>

                @role('owner')
                    <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary me-2 d-flex align-items-center">
                        <i class="fas fa-store me-1"></i> {{ __('My Stores') }}
                    </a>
                @endrole

                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2 d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                    </a>
                @endrole

                <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                    </button>
                </form>

            @endguest

            <button class="mobile-menu-toggle"><span></span><span></span><span></span></button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="mobile-menu-content">
            <a href="#home" class="mobile-nav-link"><i class="fas fa-home"></i> <span>{{ __('Home') }}</span></a>
            <a href="#services" class="mobile-nav-link"><i class="fas fa-cogs"></i>
                <span>{{ __('Services') }}</span></a>
            <a href="#demo" class="mobile-nav-link"><i class="fas fa-play"></i> <span>{{ __('Demo') }}</span></a>
            <a href="#pricing" class="mobile-nav-link"><i class="fas fa-tags"></i> <span>{{ __('Pricing') }}</span></a>

            <div class="mobile-actions">
                @guest
                    <a href="{{ route('auth.login') }}" class="btn btn-outline btn-full"><i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('Login') }}</span></a>
                    <a href="{{ route('auth.register') }}" class="btn btn-primary btn-full"><i class="fas fa-rocket"></i>
                        <span>{{ __('Register') }}</span></a>
                @else
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-full text-danger"><i
                                class="fas fa-sign-out-alt"></i> <span>{{ __('Logout') }}</span></button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Smooth scroll for anchors
        document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
            link.addEventListener('click', e => {
                const href = link.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.querySelector('.mobile-menu')?.classList.remove('open');
                }
            });
        });

        // Mobile menu toggle
        const toggle = document.querySelector('.mobile-menu-toggle');
        toggle.addEventListener('click', () => {
            document.querySelector('.mobile-menu').classList.toggle('open');
        });
    });
</script>
