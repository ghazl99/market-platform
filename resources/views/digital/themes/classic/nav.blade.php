 <!-- Header -->
 <header class="header" id="header">
     <div class="header-container">
         <!-- Logo Section -->
         @php
             $media = current_store()->getFirstMedia('logo');
         @endphp
         <div class="logo-section">
             <div class="logo">
                 <div class="logo-icon">
                     @if ($media)
                         <img src="{{ route('store.image', $media->id) }}" alt="{{ current_store()->name }}">
                     @else
                         <i class="fas fa-store fa-2x"></i>
                     @endif
                 </div>
                 <div class="logo-text">
                     <h1> {{ current_store()->name }}</h1>
                     {{-- <span class="logo-subtitle">متجرك الإلكتروني</span> --}}
                 </div>
             </div>
         </div>

         <!-- Search Section -->
         <div class="search-section">
             <div class="search-container">
                 <div class="search-input-wrapper">
                     <input type="text" placeholder="ابحث عن المنتجات..." class="search-input">
                     <button class="search-btn">
                         <i class="fas fa-search"></i>
                     </button>
                 </div>
             </div>
         </div>

         <!-- User Actions -->
         <div class="user-actions">
             <!-- Language Toggle -->
             @php
                 $currentLocale = app()->getLocale();
                 $nextLocale = $currentLocale === 'ar' ? 'en' : 'ar';
                 $nextLocaleName = $nextLocale === 'ar' ? 'العربية' : 'English';
             @endphp

             <a href="{{ LaravelLocalization::getLocalizedURL($nextLocale) }}" class="language-toggle"
                 id="languageToggle">
                 <i class="fas fa-language"></i>
                 <span style="margin-left: 6px;"></span>
             </a>

             <!-- Dark Mode Toggle -->
             <button class="dark-mode-toggle" id="darkModeToggle" onclick="toggleDarkMode()">
                 <i class="fas fa-moon"></i>
             </button>
             @auth
                 <!-- Notifications -->
                 <a class="action-btn notification-btn" href="{{ Route('notification.index') }}"
                     style="position: relative;text-decoration: none;">
                     <i class="fas fa-bell"></i>
                     @php
                         $unreadCount = auth()->user()->unreadNotifications->count();
                     @endphp
                     @if ($unreadCount > 0)
                         <span class="notification-badge">{{ $unreadCount }}</span>
                     @endif
                 </a>
             @endauth


             <!-- Mobile Menu Toggle -->
             <button class="menu-toggle" id="menuToggle">
                 <span></span>
                 <span></span>
                 <span></span>
             </button>
         </div>
     </div>
 </header>
