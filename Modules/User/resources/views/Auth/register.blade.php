<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ __('Register') }} - {{ __('My Store') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
                    <a href="{{ route('auth.login') }}" class="nav-link">{{ __('Login') }}</a>
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
                <!-- Register Form -->
                <div class="auth-form-container">
                    <div class="form-header">
                        <div class="form-logo">
                            <div class="logo-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h1>{{ __('Create a New Account') }}</h1>
                        </div>
                        <p class="form-subtitle">{{ __('Start your business journey today') }}</p>
                    </div>

                    <form method="POST" action="{{ route('auth.register') }}" class="auth-form" id="registerForm">
                        @csrf

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" class="form-input"
                                    value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" class="form-input" required>
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input" required>
                                <button type="button" class="password-toggle" id="password_confirmationToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <input type="hidden" name="role" id="role" value="owner" />

                        {{-- Terms --}}
                        <div class="form-options">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" id="agreeTerms" name="agreeTerms"
                                    {{ old('agreeTerms') ? 'checked' : '' }} required>
                                <span class="checkmark"></span>
                                <span class="checkbox-label">
                                    {{ __('I agree to') }}
                                    <a href="#" class="terms-link">{{ __('terms of use') }}</a>
                                    {{ __('and') }}
                                    <a href="#" class="terms-link">{{ __('privacy policy') }}</a>
                                </span>
                            </label>
                            @error('agreeTerms')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>{{ __('Save') }}</span>
                        </button>
                    </form>


                    <!-- Divider -->
                    <div class="divider">
                        <span>{{ __('Or') }}</span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-section">
                        <button class="social-btn google-btn" id="googleRegister">
                            <i class="fab fa-google"></i>
                            <span>{{ __('Create an account with Google') }}</span>
                        </button>
                        <button class="social-btn facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            <span>{{ __('Create an account with Facebook') }}</span>
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
            <p>{{ __('Creating account...') }}</p>
        </div>
    </div>

</body>

</html>
