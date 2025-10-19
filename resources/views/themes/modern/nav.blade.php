<style>
    /* Modern Header Styles */
    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .header-container {
        max-width: calc(100% - 300px);
        margin: 0 auto;
        margin-right: 300px;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 2rem;
        height: 80px;
        transition: all 0.3s ease;
    }

    /* Logo Section */
    .logo-section {
        display: flex;
        align-items: center;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logo:hover {
        transform: translateY(-2px);
    }

    .logo-icon {
        position: relative;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }

    .logo-glow {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: conic-gradient(from 0deg, #667eea, #764ba2, #f093fb, #667eea);
        border-radius: 14px;
        z-index: -1;
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .logo-text h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.5px;
    }

    .logo-subtitle {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Search Section */
    .search-section {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .search-container {
        width: 100%;
        max-width: 600px;
        position: relative;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 25px;
        padding: 0.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .search-input-wrapper:focus-within {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .search-input {
        background: none;
        border: none;
        outline: none;
        color: white;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        flex: 1;
        width: 100%;
        font-weight: 500;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
    }

    .search-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 0.75rem;
        border-radius: 50%;
        cursor: pointer;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        margin-left: 0.5rem;
    }

    .search-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 0.5rem;
        margin-top: 0.5rem;
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .search-input-wrapper:focus-within .search-suggestions {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .suggestion-tag {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .suggestion-tag:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    /* User Actions */
    .user-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .action-btn {
        position: relative;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        text-decoration: none;
    }

    .action-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-ripple {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.6s ease;
    }

    .action-btn:active .btn-ripple {
        width: 200px;
        height: 200px;
    }

    /* WhatsApp Button */
    .whatsapp-btn {
        background: linear-gradient(135deg, #25D366, #128C7E);
        border: none;
    }

    .whatsapp-btn:hover {
        background: linear-gradient(135deg, #128C7E, #075E54);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
    }

    /* Wallet Button */
    .wallet-btn {
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        border: none;
    }

    .wallet-btn:hover {
        background: linear-gradient(135deg, #2E7D32, #1B5E20);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    .wallet-amount {
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Notification Container */
    .notification-container {
    position: relative; /* ضروري لاحتواء العداد */
}

.notification-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: visible; /* مهم للسماح للعداد بالخروج من حدود الزر */
}

.notification-badge {
    position: absolute;
    top: -5px;      /* فوق الزر */
    right: -5px;     /* على الركن الأيمن العلوي */
    background: linear-gradient(135deg, #ff4757, #c44569);
    color: white;
    font-size: 0.7rem;
    font-weight: bold;
    padding: 0.2rem 0.4rem;
    border-radius: 50%;
    min-width: 18px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
    animation: pulse 2s infinite;
    z-index: 10; /* للتأكد أن العداد فوق كل شيء */
}


    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .notifications-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 350px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        padding: 1rem;
        margin-top: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .notification-container:hover .notifications-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .dropdown-header h4 {
        color: #333;
        margin: 0;
        font-weight: 700;
    }

    .mark-all-read {
        background: none;
        border: none;
        color: #667eea;
        font-size: 0.8rem;
        cursor: pointer;
        font-weight: 600;
    }

    .notification-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    .notification-item.unread {
        background: rgba(102, 126, 234, 0.05);
        border-left: 3px solid #667eea;
    }

    .notification-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .notification-content h5 {
        color: #333;
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .notification-content p {
        color: #666;
        margin: 0 0 0.25rem 0;
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .notification-time {
        color: #999;
        font-size: 0.7rem;
        font-weight: 500;
    }

    /* User Profile Container */
    .user-profile-container {
        position: relative;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-profile:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .user-avatar {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .status-indicator.online {
        background: #4ade80;
        animation: pulse 2s infinite;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .user-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: white;
    }

    .user-level {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .dropdown-arrow {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        transition: transform 0.3s ease;
    }

    .user-profile:hover .dropdown-arrow {
        transform: rotate(180deg);
    }

    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 200px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        padding: 0.5rem;
        margin-top: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .user-profile-container:hover .user-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #333;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .dropdown-item.logout {
        color: #ff4757;
    }

    .dropdown-item.logout:hover {
        background: rgba(255, 71, 87, 0.1);
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(0, 0, 0, 0.1);
        margin: 0.5rem 0;
    }

    /* Mobile Menu Toggle */
    .menu-toggle {
        display: none;
        flex-direction: column;
        justify-content: space-around;
        width: 30px;
        height: 30px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .menu-toggle span {
        width: 100%;
        height: 3px;
        background: white;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .menu-toggle:hover span {
        background: rgba(255, 255, 255, 0.8);
    }

    /* Header Progress Bar */
    .header-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #4ade80, #22c55e);
        width: 0%;
        transition: width 0.3s ease;
        box-shadow: 0 0 10px rgba(74, 222, 128, 0.5);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .header-container {
            margin-right: 0;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            padding: 0 1rem;
            height: 70px;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
        }

        .logo-text h1 {
            font-size: 1.4rem;
        }

        .logo-subtitle {
            display: none;
        }

        .search-section {
            display: none;
        }

        .user-actions {
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            justify-content: center;
        }

        .btn-text {
            display: none;
        }

        .wallet-amount {
            display: none;
        }

        .user-info {
            display: none;
        }

        .user-profile {
            padding: 0.5rem;
        }

        .dropdown-arrow {
            display: none;
        }

        .menu-toggle {
            display: flex;
        }

        .notifications-dropdown {
            width: 280px;
            right: -50px;
        }

        .user-dropdown {
            width: 180px;
            right: -20px;
        }
    }

    @media (max-width: 480px) {
        .header-container {
            padding: 0 0.75rem;
            height: 60px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
        }

        .logo-text h1 {
            font-size: 1.2rem;
        }

        .action-btn {
            width: 40px;
            height: 40px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
        }

        .notifications-dropdown {
            width: 250px;
            right: -80px;
        }

        .user-dropdown {
            width: 160px;
            right: -40px;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {

        .notifications-dropdown,
        .user-dropdown {
            background: rgba(30, 30, 30, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dropdown-header h4,
        .notification-content h5 {
            color: #e0e0e0;
        }

        .notification-content p {
            color: #b0b0b0;
        }

        .notification-time {
            color: #888;
        }

        .dropdown-item {
            color: #e0e0e0;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.2);
        }

        .dropdown-divider {
            background: rgba(255, 255, 255, 0.1);
        }
    }
</style>
<!-- Header -->
<header class="header">
    @php
        $media = $store->getFirstMedia('logo');
    @endphp
    <div class="header-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">
                <div class="logo-icon">
                    @if ($media)
                        <img src="{{ route('store.image', $media->id) }}" alt="{{ $store->name }}">
                    @else
                        <i class="fas fa-store fa-2x"></i>
                    @endif
                    <div class="logo-glow"></div>
                </div>
                <div class="logo-text">
                    <h1>{{ $store->name }}</h1>
                    {{-- <span class="logo-subtitle">منصة الدفع الإلكتروني</span> --}}
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <input type="text" placeholder="ابحث عن المنتجات والخدمات..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-suggestions">
                    <span class="suggestion-tag">تطبيقات الدردشة</span>
                    <span class="suggestion-tag">الألعاب</span>
                    <span class="suggestion-tag">البطاقات</span>
                </div>
            </div>
        </div>

        <!-- User Actions -->
        <div class="user-actions">
            <!-- WhatsApp Button -->
            <a href="https://api.whatsapp.com/send?phone=967733544937" class="action-btn whatsapp-btn">
                <i class="fab fa-whatsapp"></i>
                <span class="btn-text">واتساب</span>
                <div class="btn-ripple"></div>
            </a>
            @auth
                <!-- Wallet Button -->
                <button class="action-btn wallet-btn" style="background: linear-gradient(135deg, #667eea, #764ba2)">
                    <i class="fas fa-wallet"></i>
                    <span
                        class="wallet-amount">{{ number_format(Auth::user()->walletForStore()->first()?->balance ?? 0, 2) }}
                        $</span>
                    <div class="btn-ripple"></div>
                </button>

                <!-- Notifications -->
                <div class="notification-container">
                    <a class="action-btn notification-btn"
                        href="{{ Route('notification.index') }}"style="text-decoration: none">
                        <i class="fas fa-bell"></i>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp

                        @if ($unreadCount > 0)
                            <span class="notification-badge">{{ $unreadCount }}</span>
                        @endif

                    </a>
                </div>

                <!-- User Profile -->
                <div class="user-profile-container">
                    <a class="user-profile" href="{{ route('auth.profile.edit', Auth::user()->id) }}"style="text-decoration: none;">
                        <div class="user-avatar">
                            <img src="{{ Auth::user()->profilePhotoUrl }}"
                                alt="{{ Auth::user()->name }}">
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            {{-- <span class="user-level">عضو مميز</span> --}}
                        </div>
                    </a>

                </div>
            @else
                <a href="{{ route('auth.customer.login') }}" class="btn btn-success" title="{{ __('Login') }}">
                    تسجيل الدخول
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

    <!-- Header Progress Bar -->
    <div class="header-progress"></div>
</header>
