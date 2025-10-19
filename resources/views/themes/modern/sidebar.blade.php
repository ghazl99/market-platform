 <!-- Sidebar -->
 <aside class="sidebar open" id="sidebar">
     @auth

         <div class="sidebar-header">
             <div class="user-card">
                 <div class="user-avatar">
                     <img src="{{ Auth::user()->profilePhotoUrl }}" alt="{{ Auth::user()->name }}">
                 </div>
                 <div class="user-info">
                     <h4>{{ Auth::user()->name }}</h4>
                 </div>
                 <div class="balance-display">
                     <span
                         class="balance-amount">{{ number_format(Auth::user()->walletForStore()->first()?->balance ?? 0, 2) }}
                         $</span>
                 </div>
             </div>
         </div>
     @endauth
     <nav class="sidebar-nav">
         <a href="{{ Route('home') }}"
             class="nav-item {{ request()->routeIs('home') ||
             request()->routeIs('category.subCategories') ||
             request()->routeIs('category.show') ||
             request()->routeIs('product.show')
                 ? 'active'
                 : '' }}">
             <i class="fas fa-home"></i>
             <span>{{ __('Home') }}</span>
         </a>
         <a href="{{ Route('order.index') }}"
             class="nav-item {{ request()->routeIs('order.index') || request()->routeIs('order.show') ? 'active' : '' }}">
             <i class="fas fa-list"></i>
             <span>{{ __('Orders') }}</span>
         </a>
         <a href="{{ Route('wallet.index') }}"
             class="nav-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
             <i class="fas fa-wallet"></i>
             <span>{{ __('Wallet') }}</span>
         </a>

         <a href="{{ Route('payment-method.index') }}"
             class="nav-item {{ request()->routeIs('payment-method.index') ? 'active' : '' }}">
             <i class="fas fa-plus"></i>
             <span>{{ __('Balance') }}</span>
         </a>
         @auth
             <a href="{{ Route('notification.index') }}"
                 class="nav-item {{ request()->routeIs('notification.index') ? 'active' : '' }}">
                 @php
                     $unreadCount = auth()->user()->unreadNotifications->count();
                 @endphp
                 <i class="fas fa-bell"></i>
                 <span>{{ __('Notifications') }}</span>
                 <span class="notification-dot"></span>
                 @if ($unreadCount > 0)
                     <span class="menu-item-badge notification">{{ $unreadCount }}</span>
                 @endif
             </a>
             <a href="{{ Route('auth.profile.edit', Auth::user()->id) }}"
                 class="nav-item {{ request()->routeIs('auth.profile.edit', Auth::user()->id) ? 'active' : '' }}">
                 <i class="fas fa-user"></i>
                 <span>{{ __('Edit Profile') }}</span>
             </a>

             <a href="{{ Route('auth.security') }}"
                 class="nav-item {{ request()->routeIs('auth.security') || request()->routeIs('auth.change-password') ? 'active' : '' }}">
                 <i class="fas fa-user-shield"></i>
                 <span>{{ __('Security') }}</span>
             </a>

             <a href="https://api.whatsapp.com/send?phone=963992609703" class="nav-item">
                 <i class="fas fa-phone"></i>
                 <span>اتصل بنا</span>
             </a>
             <form method="POST" action="{{ route('auth.logout') }}">
                     @csrf
                     <button type="submit" class="menu-item logout-item ">
                         <div class="menu-item-icon">
                             <i class="fas fa-sign-out-alt"></i>
                         </div>
                         <span class="menu-item-title">{{ __('Logout') }}</span>
                     </button>
                 </form>
         </nav>
     @endauth
     <div class="sidebar-footer">
         {{-- <div class="dark-mode-toggle">
             <i class="fas fa-moon"></i>
             <span>الوضع الليلي</span>
             <label class="switch">
                 <input type="checkbox" id="darkModeToggle">
                 <span class="slider"></span>
             </label>
         </div> --}}
         <div class="footer-text">
             بواسطة: <a href="https://kaymn.com" target="_blank">كايمن للخدمات</a>
         </div>
     </div>
 </aside>
