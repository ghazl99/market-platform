@extends('layouts.app')

@section('title', 'التحقق من البريد الإلكتروني - منصة المتاجر')

@section('content')
<!-- Innovative Verify Email Design -->
<div class="innovative-verify-container">
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
            <div class="shape heart"></div>
            <div class="shape wave"></div>
        </div>
        <div class="floating-particles"></div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Main Content -->
    <div class="verify-content">
        <div class="container-fluid">
            <div class="row align-items-center min-vh-100">
                <!-- Left Side - Brand & Info -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="brand-section">
                        <div class="brand-animation">
                            <div class="logo-container">
                                <div class="logo-orb">
                                    <i class="fas fa-envelope-open"></i>
                                </div>
                                <div class="logo-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                        </div>
                        <div class="login-brand-text">
                            <h1 class="main-title">التحقق من البريد الإلكتروني</h1>
                            <p class="brand-description">تأكد من بريدك الإلكتروني لتفعيل حسابك</p>
                            <div class="feature-highlights">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>حماية الحساب</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>تأكيد الهوية</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-lock"></i>
                                    <span>أمان متقدم</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Verify Email Form -->
                <div class="col-lg-6">
                    <div class="verify-form-container">
                        <div class="form-card">
                            <div class="card-header">
                                <h2 class="welcome-title">التحقق من البريد الإلكتروني</h2>
                                <p class="welcome-subtitle">شكراً لك! قبل البدء، هل يمكنك التحقق من بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه لك؟</p>
                            </div>

                            @if (session('status') == 'verification-link-sent')
                                <div class="success-message">
                                    <div class="success-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="success-content">
                                        <h6>تم إرسال رابط جديد!</h6>
                                        <p>تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="verification-info">
                                <div class="info-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="info-content">
                                    <h6>لماذا نحتاج التحقق من البريد الإلكتروني؟</h6>
                                    <p>التحقق من البريد الإلكتروني يساعد في حماية حسابك والتأكد من أنك المالك الحقيقي لهذا البريد.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('auth.verification.verify') }}" class="innovative-form">
                                @csrf

                                <!-- Resend Button -->
                                <button type="submit" class="submit-button">
                                    <span class="button-text">إعادة إرسال رابط التحقق</span>
                                    <div class="button-loader">
                                        <div class="spinner"></div>
                                    </div>
                                    <div class="button-glow"></div>
                                </button>
                            </form>

                            <!-- Alternative Actions -->
                            <div class="alternative-actions">
                                <div class="divider">
                                    <span>أو</span>
                                </div>
                                <div class="action-buttons">
                                    <button class="action-btn logout-action">
                                        <div class="btn-icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <div class="btn-content">
                                            <span class="btn-title">تسجيل الخروج</span>
                                            <span class="btn-subtitle">العودة للصفحة الرئيسية</span>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div class="navigation-links">
                                <div class="link-group">
                                    <a href="{{ route('profile.edit') }}" class="nav-link">
                                        <i class="fas fa-user-edit"></i>
                                        <span>تعديل الملف الشخصي</span>
                                    </a>
                                </div>
                                <div class="link-group">
                                    <a href="{{ route('dashboard') }}" class="nav-link">
                                        <i class="fas fa-home"></i>
                                        <span>العودة للرئيسية</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Help Section -->
                            <div class="help-section">
                                <div class="help-header">
                                    <i class="fas fa-question-circle"></i>
                                    <h6>هل تحتاج مساعدة؟</h6>
                                </div>
                                <div class="help-options">
                                    <button class="help-option">
                                        <i class="fas fa-headset"></i>
                                        <span>تواصل مع الدعم الفني</span>
                                    </button>
                                    <button class="help-option">
                                        <i class="fas fa-book"></i>
                                        <span>دليل المستخدم</span>
                                    </button>
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
.innovative-verify-container {
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

.heart {
    width: 75px;
    height: 75px;
    background: linear-gradient(45deg, #ff6b9d, #c44569);
    clip-path: path('M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z');
    top: 25%;
    right: 45%;
    animation-delay: 16s;
}

.wave {
    width: 120px;
    height: 60px;
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    clip-path: path('M0,20 Q30,10 60,20 T120,20 V60 H0 Z');
    bottom: 10%;
    left: 30%;
    animation-delay: 20s;
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
.verify-content {
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

/* Verify Form Container */
.verify-form-container {
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
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.4;
}

/* Verification Info */
.verification-info {
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

.info-icon {
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

.info-content h6 {
    color: #2196f3;
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.info-content p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.4;
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

/* Alternative Actions */
.alternative-actions {
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
    background: rgba(255,255,255,0.3);
}

.divider span {
    background: rgba(255,255,255,0.1);
    padding: 0 1rem;
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.2rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 15px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
}

.action-btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: #ff6b6b;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.2);
}

.btn-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #ff6b6b;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.action-btn:hover .btn-icon {
    background: #ff6b6b;
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
    margin-bottom: 2rem;
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

/* Help Section */
.help-section {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}

.help-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: white;
}

.help-header i {
    font-size: 1.25rem;
    color: #667eea;
}

.help-header h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.help-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.help-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    color: rgba(255,255,255,0.9);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.help-option:hover {
    background: rgba(255,255,255,0.2);
    border-color: #667eea;
    transform: translateX(5px);
}

.help-option i {
    color: #667eea;
    font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 991px) {
    .brand-section {
        display: none;
    }

    .verify-form-container {
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

    .verification-info,
    .success-message {
        flex-direction: column;
        text-align: center;
    }

    .action-btn {
        flex-direction: column;
        text-align: center;
    }

    .help-options {
        gap: 0.5rem;
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

    // Alternative Action Button
    const logoutActionBtn = document.querySelector('.logout-action');
    if (logoutActionBtn) {
        logoutActionBtn.addEventListener('click', function() {
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);

            // Logout functionality
            if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
                window.location.href = '{{ route("auth.logout") }}';
            }
        });
    }

    // Help Option Buttons
    const helpOptions = document.querySelectorAll('.help-option');
    helpOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Add click effect
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'translateX(5px)';
            }, 150);

            // Handle different help options
            const text = this.querySelector('span').textContent;
            if (text.includes('الدعم الفني')) {
                alert('سيتم توجيهك إلى صفحة الدعم الفني قريباً');
            } else if (text.includes('دليل المستخدم')) {
                alert('سيتم فتح دليل المستخدم قريباً');
            }
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
