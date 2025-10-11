 <nav class="sidebar" id="sidebar">
     <!-- Sidebar Header -->
     <div class="sidebar-header">
         <div class="sidebar-brand">
             @php
                 $media = $store->getFirstMedia('logo');
             @endphp
             @if ($media)
                 <img src="{{ route('store.image', $media->id) }}" alt=">{{ $store->name }}" class="sidebar-logo">
             @else
                 <i class="fas fa-store fa-2x"></i>
             @endif
             <div class="brand-text">
                 <h2>{{ $store->name }}</h2>
                 {{-- <p>{{ $store->description }}</p> --}}
             </div>
         </div>
         <button class="sidebar-close" id="sidebarClose">
             <i class="fas fa-times"></i>
         </button>
     </div>
     @auth
         <!-- Combined Profile & Balance Section -->
         <div class="profile-balance-section">
             <div class="profile-balance-card">
                 <div class="profile-section">
                     <div class="user-avatar-container">
                         <img src="{{ Auth::user()->profilePhotoUrl }}" alt="{{ Auth::user()->name }}" class="user-avatar">
                         <div class="online-indicator"></div>
                     </div>
                     <div class="user-info">
                         <h3 class="user-name">{{ Auth::user()->name }}</h3>
                         <p class="user-email">{{ Auth::user()->email }}</p>
                     </div>
                 </div>

                 <div class="balance-section">
                     <div class="balance-info">
                         <span class="balance-label">صافي الرصيد</span>
                         <span class="balance-value">{{ number_format(Auth::user()->wallet->balance ?? 0, 2) }} $</span>
                     </div>
                     <button class="balance-refresh" title="تحديث">
                         <i class="fas fa-sync-alt"></i>
                     </button>
                 </div>
             </div>
         </div>
     @endauth
     <!-- Navigation Menu -->
     <div class="sidebar-menu">
         <div class="menu-items">
             <a href="{{ Route('home') }}"
                 class="menu-item {{ request()->routeIs('home') ||
                 request()->routeIs('category.subCategories') ||
                 request()->routeIs('category.show') ||
                 request()->routeIs('product.show')
                     ? 'active'
                     : '' }}">
                 <div class="menu-item-icon">
                     <i class="fas fa-home"></i>
                 </div>
                 <span class="menu-item-title">{{ __('Home') }}</span>
             </a>

             <a href="{{ Route('order.index') }}"
                 class="menu-item {{ request()->routeIs('order.index') || request()->routeIs('order.show') ? 'active' : '' }}">
                 <div class="menu-item-icon">
                     <i class="fas fa-list-ul"></i>
                 </div>
                 <span class="menu-item-title">{{ __('Orders') }}</span>
                 {{-- <span class="menu-item-badge">5</span> --}}
             </a>

             <a href="{{ Route('wallet.index') }}"
                 class="menu-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
                 <div class="menu-item-icon">
                     <i class="fas fa-wallet"></i>
                 </div>
                 <span class="menu-item-title">{{ __('Wallet') }}</span>
             </a>

             <a href="{{ Route('payment-method.index') }}"
                 class="menu-item {{ request()->routeIs('payment-method.index') ? 'active' : '' }}">
                 <div class="menu-item-icon">
                     <i class="fas fa-plus-circle"></i>
                 </div>
                 <span class="menu-item-title">{{ __('Balance') }}</span>
             </a>


             @auth
                 <a href="{{ Route('notification.index') }}" class="menu-item">
                     <div class="menu-item-icon">
                         <i class="fas fa-bell"></i>
                     </div>
                     <span class="menu-item-title">{{ __('Notifications') }}</span>
                     @php
                         $unreadCount = auth()->user()->unreadNotifications->count();
                     @endphp

                     @if ($unreadCount > 0)
                         <span class="menu-item-badge notification">{{ $unreadCount }}</span>
                     @endif
                 </a>
                 <a href="{{ Route('auth.profile.edit', Auth::user()->id) }}"
                     class="menu-item {{ request()->routeIs('auth.profile.edit', Auth::user()->id) ? 'active' : '' }}">
                     <div class="menu-item-icon">
                         <i class="fas fa-user"></i>
                     </div>
                     <span class="menu-item-title">{{ __('Edit Profile') }}</span>
                 </a>
                 <a href="{{ Route('auth.security') }}"
                     class="menu-item {{ request()->routeIs('auth.security') || request()->routeIs('auth.change-password') ? 'active' : '' }}">
                     <div class="menu-item-icon">
                         <i class="fas fa-user-shield"></i>
                     </div>
                     <span class="menu-item-title">{{ __('Security') }}</span>
                 </a>
                 <a href="https://api.whatsapp.com/send?phone=963992609703" class="menu-item">
                     <div class="menu-item-icon">
                         <i class="fas fa-headset"></i>
                     </div>
                     <span class="menu-item-title">الدعم</span>
                 </a>

                 <!-- Logout Item -->

                 <form method="POST" action="{{ route('auth.logout') }}">
                     @csrf
                     <button type="submit" class="menu-item logout-item ">
                         <div class="menu-item-icon">
                             <i class="fas fa-sign-out-alt"></i>
                         </div>
                         <span class="menu-item-title">{{ __('Logout') }}</span>
                     </button>
                 </form>
             @endauth
             <!-- Footer Info -->
             <div class="menu-footer-info">
                 <p>بواسطة <a href="https://kaymn.com" target="_blank">كايمن للخدمات</a></p>
                 <p class="version">الإصدار 2.0.1</p>
             </div>
         </div>
     </div>
 </nav>
