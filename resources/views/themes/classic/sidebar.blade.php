
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->

    <div class="sidebar-header">
        @auth
            <div class="profile-glass">
                <div class="profile-badge">
                    <div class="badge-avatar">
                        <img src="{{ Auth::user()->profilePhotoUrl }}" alt="{{ Auth::user()->name }}">
                        <div class="badge-glow"></div>
                    </div>
                    <div class="badge-info">
                        <div class="badge-name">{{ Auth::user()->name }}</div>
                        <div class="badge-balance">
                            <svg class="balance-icon-svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.72-2.83 0-1.57-1.34-2.48-3.03-2.91z" />
                            </svg>
                            <span>{{ number_format(Auth::user()->walletForStore()->first()?->balance ?? 0, 2) }} $</span>
                        </div>
                    </div>
                    <div class="badge-status">
                        <div class="status-glow"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="profile-glass guest-login">
                <div class="profile-badge">
                    <div class="badge-info text-center w-100">
                        <div class="badge-name">{{ __('Welcome, Guest!') }}</div>
                        <a href="{{ route('auth.customer.login') }}" style="text-decoration: none">
                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav-pro">
        <a href="{{ Route('home') }}"
            class="nav-item-pro {{ request()->routeIs('home') ||
            request()->routeIs('category.subCategories') ||
            request()->routeIs('category.show') ||
            request()->routeIs('product.show')
                ? 'active'
                : '' }}">
            <div class="nav-icon-box">
                <i class="fas fa-home"></i>
            </div>
            <span class="nav-label">{{ __('Home') }}</span>
            <div class="active-indicator"></div>
        </a>

        <a href="{{ Route('order.index') }}"
            class="nav-item-pro {{ request()->routeIs('order.index') || request()->routeIs('order.show') ? 'active' : '' }}">
            <div class="nav-icon-box">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="nav-label">{{ __('Orders') }}</span>
        </a>

        <a href="{{ Route('wallet.index') }}"
            class="nav-item-pro {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
            <div class="nav-icon-box">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="nav-label">{{ __('Wallet') }}</span>
        </a>

        <a href="{{ route('wallet.payment-requests.index') }}"
            class="nav-item-pro {{ request()->routeIs('wallet.payment-requests.*') ? 'active' : '' }}">
            <div class="nav-icon-box">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <span class="nav-label"> {{ __('Payment Requests') }}</span>
        </a>

        <a href="{{ Route('payment-method.index') }}"
            class="nav-item-pro {{ request()->routeIs('payment-method.index') ? 'active' : '' }}">
            <div class="nav-icon-box">
                <i class="fas fa-plus-circle"></i>
            </div>
            <span class="nav-label"> {{ __('Balance') }}</span>
        </a>
        @auth
            <a href="{{ Route('notification.index') }}"
                class="nav-item-pro {{ request()->routeIs('notification.index') ? 'active' : '' }}">
                <div class="nav-icon-box">
                    <i class="fas fa-bell"></i>
                </div>
                <span class="nav-label">{{ __('Notifications') }}</span>
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp

                @if ($unreadCount > 0)
                    <span class="notification-mark">{{ $unreadCount }}</span>
                @endif
            </a>

            <a href="{{ Route('auth.profile.edit', Auth::user()->id) }}"
                class="nav-item-pro {{ request()->routeIs('auth.profile.edit', Auth::user()->id) ? 'active' : '' }}">
                <div class="nav-icon-box">
                    <i class="fas fa-cog"></i>
                </div>
                <span class="nav-label">{{ __('Edit Profile') }}</span>
            </a>

            <a href="{{ Route('auth.security') }}"
                class="nav-item-pro {{ request()->routeIs('auth.security') || request()->routeIs('auth.change-password') ? 'active' : '' }}">
                <div class="nav-icon-box">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span class="nav-label">{{ __('Security') }}</span>
            </a>

            <a href="https://api.whatsapp.com/send?phone=967733544937" class="nav-item-pro whatsapp">
                <div class="nav-icon-box">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <span class="nav-label">اتصل بنا</span>
            </a>

            <div class="nav-divider"></div>
            <form method="POST" action="{{ route('auth.customer.logout') }}">
                @csrf
                <button class="logout-btn-pro" type="submit">
                    <div class="logout-icon-box">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span> {{ __('Logout') }}</span>
                </button>
            </form>
        @endauth
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer-pro">
        <p>بواسط <a href="https://kaymn.com" target="_blank">منصة متجري</a></p>
    </div>
</aside>
