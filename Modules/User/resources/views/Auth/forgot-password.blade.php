@extends('layouts.app')

@section('title', 'استعادة كلمة المرور - منصة المتاجر')

@section('content')
    <!-- Innovative Forgot Password Design -->
    <div class="innovative-forgot-container">
        <!-- Animated Background -->
        <div class="animated-background">
            <div class="geometric-shapes">
                <div class="shape hexagon"></div>
                <div class="shape circle"></div>
                <div class="shape triangle"></div>
                <div class="shape square"></div>
                <div class="shape pentagon"></div>
                <div class="shape star"></div>
                <div class="shape diamond"></div>
            </div>
            <div class="floating-particles"></div>
            <div class="gradient-overlay"></div>
        </div>

        <!-- Main Content -->
        <div class="forgot-content">
            <div class="container-fluid">
                <div class="row align-items-center min-vh-100">
                    <!-- Left Side - Brand & Info -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="brand-section">
                            <div class="brand-animation">
                                <div class="logo-container">
                                    <div class="logo-orb">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="logo-rings">
                                        <div class="ring ring-1"></div>
                                        <div class="ring ring-2"></div>
                                        <div class="ring ring-3"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="login-brand-text">
                                <h1 class="main-title">استعادة كلمة المرور</h1>
                                <p class="brand-description">نحن هنا لمساعدتك في استعادة حسابك</p>
                                <div class="feature-highlights">
                                    <div class="feature-item">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>أمان عالي</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-clock"></i>
                                        <span>استعادة سريعة</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-headset"></i>
                                        <span>دعم فني 24/7</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Forgot Password Form -->
                    <div class="col-lg-6">
                        <div class="forgot-form-container">
                            <div class="form-card">
                                <div class="card-header">
                                    <h2 class="welcome-title">نسيت كلمة المرور؟</h2>
                                    <p class="welcome-subtitle">أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين</p>
                                </div>

                                @if (session('status'))
                                    <div class="success-message">
                                        <div class="success-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="success-content">
                                            <h6>تم إرسال الرابط!</h6>
                                            <p>{{ session('status') }}</p>
                                        </div>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}" class="innovative-form">
                                    @csrf

                                    <!-- Email Field -->
                                    <div class="form-field-group">
                                        <div class="field-container">
                                            <div class="input-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <input type="email"
                                                class="innovative-input @error('email') is-invalid @enderror" id="email"
                                                name="email" value="{{ old('email') }}" required autofocus
                                                placeholder="البريد الإلكتروني">
                                            <div class="field-highlight"></div>
                                        </div>
                                        @error('email')
                                            <div class="error-display">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="submit-button">
                                        <span class="button-text">إرسال رابط إعادة التعيين</span>
                                        <div class="button-loader">
                                            <div class="spinner"></div>
                                        </div>
                                        <div class="button-glow"></div>
                                    </button>
                                </form>

                                <!-- Alternative Options -->
                                <div class="alternative-section">
                                    <div class="divider">
                                        <span>أو</span>
                                    </div>
                                    <div class="alternative-options">
                                        <button class="alternative-btn contact-support">
                                            <div class="btn-icon">
                                                <i class="fas fa-headset"></i>
                                            </div>
                                            <div class="btn-content">
                                                <span class="btn-title">تواصل مع الدعم الفني</span>
                                                <span class="btn-subtitle">للمساعدة المباشرة</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Navigation Links -->
                                <div class="navigation-links">
                                    <div class="link-group">
                                        <a href="{{ route('auth.login') }}" class="nav-link">
                                            <i class="fas fa-arrow-left"></i>
                                            <span>العودة لتسجيل الدخول</span>
                                        </a>
                                    </div>
                                    <div class="link-group">
                                        <a href="{{ route('auth.register') }}" class="nav-link">
                                            <i class="fas fa-user-plus"></i>
                                            <span>إنشاء حساب جديد</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Innovative Styles -->
    <style>
        .innovative-forgot-container {
            position: relative;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            overflow: hidden;
        }

        /* Animated Background */
        .animated-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .geometric-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: floatShape 20s infinite linear;
        }

        .hexagon {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            border-radius: 50%;
            top: 60%;
            right: 15%;
            animation-delay: 5s;
        }

        .triangle {
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 86px solid #ff9a9e;
            top: 40%;
            left: 20%;
            animation-delay: 10s;
        }

        .square {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #ffecd2, #fcb69f);
            transform: rotate(45deg);
            bottom: 30%;
            right: 25%;
            animation-delay: 15s;
        }

        .pentagon {
            width: 90px;
            height: 90px;
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
            bottom: 20%;
            left: 15%;
            animation-delay: 7s;
        }

        .star {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #ffd89b, #19547b);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            top: 80%;
            right: 10%;
            animation-delay: 12s;
        }

        .diamond {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            transform: rotate(45deg);
            top: 10%;
            right: 30%;
            animation-delay: 18s;
        }

        @keyframes floatShape {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }

            25% {
                transform: translateY(-20px) rotate(90deg) scale(1.1);
            }

            50% {
                transform: translateY(-40px) rotate(180deg) scale(0.9);
            }

            75% {
                transform: translateY(-20px) rotate(270deg) scale(1.05);
            }
        }

        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255, 255, 255, 0.6), transparent),
                radial-gradient(2px 2px at 160px 30px, #ddd, transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: particleMove 20s linear infinite;
        }

        @keyframes particleMove {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-200px);
            }
        }

        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(255, 119, 198, 0.3) 0%, transparent 50%);
        }

        /* Main Content */
        .forgot-content {
            position: relative;
            z-index: 10;
        }

        /* Brand Section */
        .brand-section {
            padding: 4rem 2rem;
            text-align: center;
            color: white;
        }

        .login-brand-text {
            margin-top: 2rem;
        }

        .brand-animation {
            margin-bottom: 3rem;
        }

        .logo-container {
            position: relative;
            display: inline-block;
        }

        .logo-orb {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            }
        }

        .logo-rings {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .ring {
            position: absolute;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: ringExpand 3s ease-in-out infinite;
        }

        .ring-1 {
            width: 140px;
            height: 140px;
            animation-delay: 0s;
        }

        .ring-2 {
            width: 180px;
            height: 180px;
            animation-delay: 1s;
        }

        .ring-3 {
            width: 220px;
            height: 220px;
            animation-delay: 2s;
        }

        @keyframes ringExpand {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.6;
            }
        }

        .main-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .brand-description {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
            font-weight: 500;
        }

        .feature-highlights {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: auto;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.2);
        }

        .feature-item i {
            font-size: 1.5rem;
            color: #667eea;
        }

        /* Forgot Form Container */
        .forgot-form-container {
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .form-card:hover::before {
            left: 100%;
        }

        .card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Success Message */
        .success-message {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .success-icon {
            width: 50px;
            height: 50px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .success-content h6 {
            color: #28a745;
            margin: 0 0 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .success-content p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        /* Form Fields */
        .form-field-group {
            margin-bottom: 2rem;
        }

        .field-container {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .field-container:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 2;
        }

        .innovative-input {
            width: 100%;
            padding: 1.2rem 1rem 1.2rem 3rem;
            background: transparent;
            border: none;
            color: white;
            font-size: 1rem;
            outline: none;
        }

        .innovative-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .field-highlight {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.3s ease;
        }

        .field-container:focus-within .field-highlight {
            width: 100%;
        }

        .error-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ff6b6b;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 107, 107, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .button-text {
            position: relative;
            z-index: 2;
        }

        .button-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .submit-button.loading .button-text {
            opacity: 0;
        }

        .submit-button.loading .button-loader {
            display: block;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .button-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .submit-button:hover .button-glow {
            left: 100%;
        }

        /* Alternative Section */
        .alternative-section {
            margin-bottom: 2rem;
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 1.5rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .divider span {
            background: rgba(255, 255, 255, 0.1);
            padding: 0 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .alternative-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .alternative-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
        }

        .alternative-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .btn-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #667eea;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .alternative-btn:hover .btn-icon {
            background: #667eea;
            color: white;
        }

        .btn-content {
            flex: 1;
        }

        .btn-title {
            display: block;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .btn-subtitle {
            display: block;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        /* Navigation Links */
        .navigation-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .link-group {
            text-align: center;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-link:hover {
            color: #667eea;
            background: rgba(255, 255, 255, 0.1);
            border-color: #667eea;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .nav-link i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .nav-link:hover i {
            transform: translateX(-3px);
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .brand-section {
                display: none;
            }

            .forgot-form-container {
                min-height: 100vh;
                padding: 1rem;
            }

            .form-card {
                padding: 2rem;
            }
        }

        @media (max-width: 576px) {
            .form-card {
                padding: 1.5rem;
                margin: 1rem;
            }

            .main-title {
                font-size: 2.5rem;
            }

            .success-message {
                flex-direction: column;
                text-align: center;
            }

            .alternative-btn {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <!-- Innovative JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form Submission Animation
            const form = document.querySelector('.innovative-form');
            const submitButton = document.querySelector('.submit-button');

            if (form && submitButton) {
                form.addEventListener('submit', function() {
                    submitButton.classList.add('loading');

                    // Simulate loading (remove in production)
                    setTimeout(() => {
                        submitButton.classList.remove('loading');
                    }, 2000);
                });
            }

            // Input Focus Effects
            const inputs = document.querySelectorAll('.innovative-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });

            // Alternative Button Interactions
            const alternativeBtn = document.querySelector('.contact-support');
            if (alternativeBtn) {
                alternativeBtn.addEventListener('click', function() {
                    // Add ripple effect
                    const ripple = document.createElement('div');
                    ripple.className = 'ripple';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);

                    // Show contact support message
                    alert('سيتم توجيهك إلى صفحة الدعم الفني قريباً');
                });
            }

            // Add floating animation to form card
            const formCard = document.querySelector('.form-card');
            if (formCard) {
                formCard.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });

                formCard.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            }

            // Success message animation
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                successMessage.style.animation = 'slideInUp 0.6s ease-out';
            }

            // Add CSS for slideInUp animation
            const style = document.createElement('style');
            style.textContent = `
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
            document.head.appendChild(style);
        });
    </script>
@endsection
