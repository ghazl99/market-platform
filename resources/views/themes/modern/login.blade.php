<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ __('Login') }} - {{ __('My Store') }}</title>
    <style>
        :root {
            --primary-color: #7C3AED;
            /* Purple 600 */
            --primary-dark: #5B21B6;
            /* Purple 800 */
            --primary-light: #C4B5FD;
            /* Purple 300 */
            --secondary-color: #8B5CF6;
            --secondary-dark: #6D28D9;
            --success-color: #10B981;
            --error-color: #EF4444;
            --warning-color: #F59E0B;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #F9FAFB;
            --bg-secondary: #FFFFFF;
            --bg-accent: #F3E8FF;
            --border-color: #E5E7EB;
            --border-light: #EDE9FE;
            --gradient-primary: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #7C3AED;
            /* Purple 600 */
            --primary-dark: #5B21B6;
            /* Purple 800 */
            --primary-light: #C4B5FD;
            /* Purple 300 */
            --secondary-color: #8B5CF6;
            --secondary-dark: #6D28D9;
            --success-color: #10B981;
            --error-color: #EF4444;
            --warning-color: #F59E0B;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #F9FAFB;
            --bg-secondary: #FFFFFF;
            --bg-accent: #F3E8FF;
            --border-color: #E5E7EB;
            --border-light: #EDE9FE;
            --gradient-primary: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }
    </style>
</head>

<body class="auth-body">
    <!-- Header -->
    <header class="auth-header">
        <div class="container">
            <div class="auth-nav">
                <a href="index.html" class="auth-logo">
                    <div class="logo-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name">{{ __('Mtjree') }}</span>
                        <span class="logo-tagline">{{ __('Smart Commerce Platform') }}</span>
                    </div>
                </a>
                <div class="auth-nav-links">
                    <a href="{{ route('home') }}" class="nav-link">{{ __('Home') }}</a>
                    <a href="{{ route('auth.customer.register') }}" class="nav-link">{{ __('Register') }}</a>
                </div>
                <div class="language-switcher d-flex gap-2">
                    <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                        class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary' : 'btn-outline-secondary' }}">عربي</a>
                    <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                        class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">EN</a>
                </div>
            </div>

        </div>
    </header>
    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-background">
            <div class="auth-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>

        <div class="container">
            <div class="auth-wrapper">
                <!-- Login Form -->
                <div class="auth-form-container">
                    <div class="form-header">
                        <div class="form-logo">
                            <div class="logo-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h1>{{ __('Login') }}</h1>
                        </div>
                        <p class="form-subtitle">{{ __('Welcome back! Log in to access your account') }}</p>
                    </div>

                    <form method="POST" action="{{ route('auth.customer.login') }}" class="auth-form" id="loginForm">
                        @csrf

                        <input type="text" hidden name="fcm_token" id="fcm_token">
                        <input type="text" hidden name="device_type" id="device_type">
                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="form-input @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="error-display">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="form-input @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="error-display">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-options">
                            <input type="hidden" name="remember" value="0">

                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="remember" value="1" class="custom-checkbox"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                <span class="checkbox-label">{{ __('Remember me') }}</span>
                            </label>
                            <a href="#" class="forgot-link">{{ __('Forgot password?') }}</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>{{ __('Login') }}</span>
                        </button>

                        <!-- Display General Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>


                    <!-- Divider -->
                    <div class="divider">
                        <span>{{ __('Or') }}</span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-section">
                        <button class="social-btn google-btn" id="googleLogin">
                            <i class="fab fa-google"></i>
                            <span>{{ __('Login with Google') }}</span>
                        </button>
                        <button class="social-btn facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            <span>{{ __('Login with Facebook') }}</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>{{ __('Logging in...') }}</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('auth'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: '{{ __('Attention') }}',
                text: '{{ session('auth') }}',
                confirmButtonText: '{{ __('OK') }}'
            });
        </script>
    @endif
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-analytics.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-messaging.js";
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyCfHDvHfXQFh86Q0xu-EisbFORMl2llGME",
            authDomain: "market-platform-b93fa.firebaseapp.com",
            projectId: "market-platform-b93fa",
            storageBucket: "market-platform-b93fa.firebasestorage.app",
            messagingSenderId: "859095628707",
            appId: "1:859095628707:web:f21f330779630d12cb3816",
            measurementId: "G-1S0ZSR2NVM"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);
        // طلب إذن الإشعارات
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // الحصول على التوكن الخاص بالمتصفح
                getToken(messaging, {
                    vapidKey: 'BKNcEWKYHCcvAhWB4XjX5wxtHaP1lB1htaaqFp4YDEz2jPGFdBLNCpV_uNCTPIjulkaoCfRS9udqlIoCgHOJomc'
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log('FCM Token:', currentToken);
                        document.getElementById('fcm_token').value = currentToken;
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });
    </script>
    <script>
        // تحديد نوع الجهاز بشكل ذكي
        function detectDeviceType() {
            const ua = navigator.userAgent;

            if (/android/i.test(ua)) return 'Android';
            if (/iPad|iPhone|iPod/.test(ua)) return 'iOS';
            if (/Windows NT/i.test(ua)) return 'Windows';
            if (/Macintosh/i.test(ua)) return 'Mac';
            if (/Linux/i.test(ua)) return 'Linux';

            return 'Unknown';
        }

        // تحديد نوع المتصفح
        function detectBrowser() {
            const ua = navigator.userAgent;
            if (ua.includes("Chrome")) return "Chrome";
            if (ua.includes("Firefox")) return "Firefox";
            if (ua.includes("Safari") && !ua.includes("Chrome")) return "Safari";
            if (ua.includes("Edge")) return "Edge";
            if (ua.includes("OPR") || ua.includes("Opera")) return "Opera";
            return "Other";
        }

        // حفظ النتائج في الحقول المخفية
        const deviceInfo = detectDeviceType() + ' - ' + detectBrowser();
        document.getElementById('device_type').value = deviceInfo;

        // طباعة القيم بالمونسول
        console.log("Device Type:", detectDeviceType());
        console.log("Browser:", detectBrowser());
        console.log("Combined Info:", deviceInfo);
    </script>
</body>

</html>
