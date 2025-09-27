@extends('layouts.app')

@section('title', 'إعادة تعيين كلمة المرور - منصة المتاجر')

@section('content')
<!-- Innovative Reset Password Design -->
<div class="innovative-reset-container">
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
            <div class="shape octagon"></div>
        </div>
        <div class="floating-particles"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Main Content -->
    <div class="reset-content">
        <div class="container-fluid">
            <div class="row align-items-center min-vh-100">
                <!-- Left Side - Brand & Info -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="brand-section">
                        <div class="brand-animation">
                            <div class="logo-container">
                                <div class="logo-orb">
                                    <i class="fas fa-lock-open"></i>
                                </div>
                                <div class="logo-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                        </div>
                        <div class="login-brand-text">
                            <h1 class="main-title">إعادة تعيين كلمة المرور</h1>
                            <p class="brand-description">أنشئ كلمة مرور جديدة وآمنة لحسابك</p>
                            <div class="feature-highlights">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>أمان عالي</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-key"></i>
                                    <span>تشفير قوي</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-user-shield"></i>
                                    <span>حماية متقدمة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Reset Password Form -->
                <div class="col-lg-6">
                    <div class="reset-form-container">
                        <div class="form-card">
                            <div class="card-header">
                                <h2 class="welcome-title">إنشاء كلمة مرور جديدة</h2>
                                <p class="welcome-subtitle">أدخل كلمة المرور الجديدة وتأكيدها</p>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}" class="innovative-form">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Field -->
                                <div class="form-field-group">
                                    <div class="field-container">
                                        <div class="input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input type="email"
                                               class="innovative-input @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $request->email) }}"
                                               required
                                               autofocus
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

                                <!-- Password Field -->
                                <div class="form-field-group">
                                    <div class="field-container">
                                        <div class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <input type="password"
                                               class="innovative-input @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               required
                                               placeholder="كلمة المرور الجديدة">
                                        <button type="button" class="password-visibility" id="passwordToggle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="field-highlight"></div>
                                    </div>
                                    @error('password')
                                        <div class="error-display">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <!-- Password Strength Indicator -->
                                    <div class="password-strength">
                                        <div class="strength-bar">
                                            <div class="strength-fill" id="strengthFill"></div>
                                        </div>
                                        <span class="strength-text" id="strengthText">قوة كلمة المرور</span>
                                    </div>
                                </div>

                                <!-- Password Confirmation Field -->
                                <div class="form-field-group">
                                    <div class="field-container">
                                        <div class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <input type="password"
                                               class="innovative-input @error('password_confirmation') is-invalid @enderror"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               required
                                               placeholder="تأكيد كلمة المرور">
                                        <button type="button" class="password-visibility" id="confirmPasswordToggle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="field-highlight"></div>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="error-display">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Requirements -->
                                <div class="password-requirements">
                                    <h6>متطلبات كلمة المرور:</h6>
                                    <ul class="requirements-list">
                                        <li class="requirement" data-requirement="length">
                                            <i class="fas fa-circle"></i>
                                            <span>8 أحرف على الأقل</span>
                                        </li>
                                        <li class="requirement" data-requirement="uppercase">
                                            <i class="fas fa-circle"></i>
                                            <span>حرف كبير واحد على الأقل</span>
                                        </li>
                                        <li class="requirement" data-requirement="lowercase">
                                            <i class="fas fa-circle"></i>
                                            <span>حرف صغير واحد على الأقل</span>
                                        </li>
                                        <li class="requirement" data-requirement="number">
                                            <i class="fas fa-circle"></i>
                                            <span>رقم واحد على الأقل</span>
                                        </li>
                                        <li class="requirement" data-requirement="special">
                                            <i class="fas fa-circle"></i>
                                            <span>رمز خاص واحد على الأقل</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="submit-button">
                                    <span class="button-text">إعادة تعيين كلمة المرور</span>
                                    <div class="button-loader">
                                        <div class="spinner"></div>
                                    </div>
                                    <div class="button-glow"></div>
                                </button>
                            </form>

                            <!-- Security Notice -->
                            <div class="security-notice">
                                <div class="notice-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="notice-content">
                                    <h6>ملاحظة أمنية</h6>
                                    <p>تأكد من اختيار كلمة مرور قوية وفريدة لحسابك. لا تشاركها مع أي شخص.</p>
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
.innovative-reset-container {
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

.octagon {
    width: 85px;
    height: 85px;
    background: linear-gradient(45deg, #ff9a9e, #fecfef);
    clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
    top: 70%;
    left: 40%;
    animation-delay: 14s;
}

@keyframes floatShape {
    0%, 100% {
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
        radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
        radial-gradient(1px 1px at 90px 40px, #fff, transparent),
        radial-gradient(1px 1px at 130px 80px, rgba(255,255,255,0.6), transparent),
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
.reset-content {
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
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    animation: logoPulse 3s ease-in-out infinite;
}

@keyframes logoPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
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
    border: 2px solid rgba(255,255,255,0.3);
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
    0%, 100% {
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
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.brand-description {
    font-size: 1.2rem;
    margin-bottom: 3rem;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
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
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.feature-item:hover {
    transform: translateX(10px);
    background: rgba(255,255,255,0.2);
}

.feature-item i {
    font-size: 1.5rem;
    color: #667eea;
}

/* Reset Form Container */
.reset-form-container {
    padding: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.form-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 3rem;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    width: 100%;
    max-width: 500px;
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
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
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
    color: rgba(255,255,255,0.8);
    font-size: 1rem;
    line-height: 1.5;
}

/* Form Fields */
.form-field-group {
    margin-bottom: 2rem;
}

.field-container {
    position: relative;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
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
    color: rgba(255,255,255,0.7);
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
    color: rgba(255,255,255,0.6);
}

.password-visibility {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: rgba(255,255,255,0.7);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    z-index: 2;
}

.password-visibility:hover {
    color: #667eea;
    background: rgba(255,255,255,0.1);
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

/* Password Strength Indicator */
.password-strength {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
}

.strength-bar {
    width: 100%;
    height: 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.strength-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #ff6b6b, #ffa726, #66bb6a);
    transition: width 0.3s ease;
    border-radius: 3px;
}

.strength-text {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.8);
}

/* Password Requirements */
.password-requirements {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.1);
}

.password-requirements h6 {
    color: white;
    margin-bottom: 1rem;
    font-size: 1rem;
    font-weight: 600;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.requirement {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.requirement i {
    font-size: 0.75rem;
    transition: all 0.3s ease;
}

.requirement.valid {
    color: #66bb6a;
}

.requirement.valid i {
    color: #66bb6a;
}

.requirement.invalid {
    color: #ff6b6b;
}

.requirement.invalid i {
    color: #ff6b6b;
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
    border: 2px solid rgba(255,255,255,0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.button-glow {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.submit-button:hover .button-glow {
    left: 100%;
}

/* Security Notice */
.security-notice {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.3);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.notice-icon {
    width: 50px;
    height: 50px;
    background: #2196f3;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.notice-content h6 {
    color: #2196f3;
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.notice-content p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.4;
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
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
}

.nav-link:hover {
    color: #667eea;
    background: rgba(255,255,255,0.1);
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

    .reset-form-container {
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

    .password-requirements {
        padding: 1rem;
    }

    .requirements-list {
        gap: 0.5rem;
    }
}
</style>

<!-- Innovative JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password Toggle Functionality
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    if (confirmPasswordToggle && confirmPasswordInput) {
        confirmPasswordToggle.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Password Strength Indicator
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    if (passwordInput && strengthFill && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrength(strength);
            updatePasswordRequirements(password);
        });
    }

    function calculatePasswordStrength(password) {
        let score = 0;

        if (password.length >= 8) score += 20;
        if (/[A-Z]/.test(password)) score += 20;
        if (/[a-z]/.test(password)) score += 20;
        if (/[0-9]/.test(password)) score += 20;
        if (/[^A-Za-z0-9]/.test(password)) score += 20;

        return score;
    }

    function updatePasswordStrength(strength) {
        strengthFill.style.width = strength + '%';

        if (strength <= 20) {
            strengthFill.style.background = '#ff6b6b';
            strengthText.textContent = 'ضعيف جداً';
        } else if (strength <= 40) {
            strengthFill.style.background = '#ffa726';
            strengthText.textContent = 'ضعيف';
        } else if (strength <= 60) {
            strengthFill.style.background = '#ffd54f';
            strengthText.textContent = 'متوسط';
        } else if (strength <= 80) {
            strengthFill.style.background = '#66bb6a';
            strengthText.textContent = 'قوي';
        } else {
            strengthFill.style.background = '#4caf50';
            strengthText.textContent = 'قوي جداً';
        }
    }

    function updatePasswordRequirements(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        Object.keys(requirements).forEach(req => {
            const element = document.querySelector(`[data-requirement="${req}"]`);
            if (element) {
                if (requirements[req]) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                    element.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    element.classList.add('invalid');
                    element.classList.remove('valid');
                    element.querySelector('i').className = 'fas fa-circle';
                }
            }
        });
    }

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
