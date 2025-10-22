<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }}</title>

    <!-- Mobile optimizations -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $store->name }}>
    <meta name="format-detection"
        content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#059669">
    <meta name="msapplication-TileColor" content="#059669">
    <meta name="msapplication-config" content="browserconfig.xml">

    <!-- Icons -->
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icon-16x16.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles-store.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body class="modern-layout">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-container">
            @php
                $media = $store->getFirstMedia('logo');
            @endphp
            <div class="loading-logo">
                <div class="logo-icon">
                    <i class="fas fa-store"></i>

                </div>
                <div class="logo-text">منصة المتاجر</div>
            </div>
            <div class="loading-progress">
                <div class="progress-bar-loading">
                    <div class="progress-fill" id="loadingProgress"></div>
                </div>
                <div class="loading-text" id="loadingText">جاري التحميل...</div>
                <div class="loading-percentage" id="loadingPercentage">0%</div>
            </div>
            <div class="loading-animation">
                <div class="dot-pulse">
                    <div class="dot1"></div>
                    <div class="dot2"></div>
                    <div class="dot3"></div>
                </div>
            </div>
        </div>
        <div class="loading-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <!-- Page Progress Indicator -->
    <div class="page-progress-container">
        <div class="page-progress-bar" id="pageProgressBar"></div>
        <div class="progress-circle" id="progressCircle">
            <div class="progress-text" id="progressText">0%</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="brand-icon me-2">
                    <img src="{{ route('store.image', $media->id) }}" alt="Store Logo" class="img-fluid"
                        style="max-height: 80px;">
                </div>
                <span class="brand-text">{{ $store->name }}</span>
            </a>


            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}"
                            href="{{ route('category.index') }}">
                            <i class="fas fa-list me-1"></i>
                            {{ __('Categories') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-box-open me-1"></i>
                            {{ __('Products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('carts.index') ? 'active' : '' }}"
                            href="{{ route('carts.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>
                            {{ __('Cart') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('order.index') || request()->routeIs('order.show') ? 'active' : '' }}"
                            href="{{ route('order.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>
                            {{ __('Orders') }}
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-2">
                        <select class="form-select w-auto" onchange="window.location.href=this.value">
                            <option value="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                                {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>
                                AR - العربية
                            </option>
                            <option value="{{ LaravelLocalization::getLocalizedURL('en') }}"
                                {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                                EN - English
                            </option>
                        </select>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item me-2">
                            <a class="nav-link btn btn-outline-light btn-sm px-3 py-2 rounded-pill"
                                href="{{ route('auth.login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                {{ __('Login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-warning btn-sm px-3 py-2 rounded-pill text-dark"
                                href="{{ route('auth.register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end modern-dropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('stores.index') }}">
                                        <i class="fas fa-store me-2"></i>
                                        {{ __('My Stores') }}
                                    </a>
                                </li>
                                @role('admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            {{ __('Dashboard') }}
                                        </a>
                                    </li>
                                @endrole
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('auth.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="container mt-5 pt-5">
                <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-5 pt-5">
                <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-brand">
                            <div class="brand-icon-large mb-3">
                                <i class="fas fa-store"></i>
                            </div>
                            <h5 class="text-white fw-bold mb-3">منصة المتاجر</h5>
                            <p class="text-light opacity-75">
                                منصة متكاملة لإنشاء وإدارة المتاجر الإلكترونية مع دومين خاص ولوحة تحكم احترافية
                            </p>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="text-white fw-bold mb-3">{{ __('Services') }}</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('services') }}">{{ __('Create Stores') }}</a></li>
                            <li><a href="{{ route('services') }}">{{ __('Manage Stores') }}</a></li>
                            <li><a href="{{ route('services') }}">{{ __('Technical Support') }}</a></li>
                            <li><a href="{{ route('services') }}">{{ __('Customization') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="text-white fw-bold mb-3">{{ __('About Platform') }}</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">{{ __('About Platform') }}</a></li>
                            <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>
                            <li><a href="{{ route('pricing') }}">{{ __('Pricing') }}</a></li>
                            <li><a href="#">{{ __('Demo') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <h6 class="text-white fw-bold mb-3">{{ __('Newsletter') }}</h6>
                        <p class="text-light opacity-75 mb-3">
                            {{ __('Subscribe for latest updates and special offers') }}
                        </p>
                        <div class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="{{ __('Your Email') }}">
                                <button class="btn btn-warning" type="button">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="footer-divider">

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-light mb-0">
                            &copy; {{ date('Y') }} {{ __('Market Platform') }}. {{ __('All rights reserved.') }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-bottom-links">
                            <a href="#" class="text-light me-3">{{ __('Privacy Policy') }}</a>
                            <a href="#" class="text-light me-3">{{ __('Terms of Use') }}</a>
                            <a href="#" class="text-light">{{ __('Site Map') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Loading Screen Script -->
    <script>
        class LoadingManager {
            constructor() {
                this.loadingScreen = document.getElementById('loadingScreen');
                this.loadingProgress = document.getElementById('loadingProgress');
                this.loadingText = document.getElementById('loadingText');
                this.loadingPercentage = document.getElementById('loadingPercentage');

                this.progress = 0;
                this.loadingSteps = [{
                        text: "تحضير الواجهة...",
                        duration: 300
                    },
                    {
                        text: "تحميل الأنماط...",
                        duration: 200
                    },
                    {
                        text: "تحميل البيانات...",
                        duration: 400
                    },
                    {
                        text: "تحميل الصور...",
                        duration: 500
                    },
                    {
                        text: "تحسين الأداء...",
                        duration: 300
                    },
                    {
                        text: "اللمسات الأخيرة...",
                        duration: 200
                    }
                ];

                this.currentStep = 0;
                this.init();
            }

            init() {
                // بدء عملية التحميل
                this.startLoading();

                // إخفاء شاشة التحميل عند تحميل الصفحة بالكامل
                if (document.readyState === 'complete') {
                    this.completeLoading();
                } else {
                    window.addEventListener('load', () => {
                        this.completeLoading();
                    });
                }

                // إخفاء اضطراري بعد 5 ثوان
                setTimeout(() => {
                    if (!this.loadingScreen.classList.contains('fade-out')) {
                        this.completeLoading();
                    }
                }, 5000);
            }

            startLoading() {
                this.simulateLoading();
            }

            simulateLoading() {
                if (this.currentStep < this.loadingSteps.length) {
                    const step = this.loadingSteps[this.currentStep];
                    this.loadingText.textContent = step.text;

                    const targetProgress = ((this.currentStep + 1) / this.loadingSteps.length) * 90; // 90% للمحاكاة

                    this.animateProgress(this.progress, targetProgress, step.duration, () => {
                        this.currentStep++;
                        setTimeout(() => {
                            this.simulateLoading();
                        }, 100);
                    });
                } else {
                    // المرحلة الأخيرة - إكمال التحميل
                    this.loadingText.textContent = "تم التحميل بنجاح!";
                    this.animateProgress(this.progress, 100, 300, () => {
                        setTimeout(() => {
                            this.completeLoading();
                        }, 500);
                    });
                }
            }

            animateProgress(from, to, duration, callback) {
                const startTime = performance.now();
                const progressDiff = to - from;

                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // منحنى الحركة السلس
                    const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                    const currentProgress = from + (progressDiff * easeOutCubic);

                    this.updateProgress(currentProgress);

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        this.progress = to;
                        if (callback) callback();
                    }
                };

                requestAnimationFrame(animate);
            }

            updateProgress(progress) {
                const roundedProgress = Math.round(progress);
                this.loadingProgress.style.width = progress + '%';
                this.loadingPercentage.textContent = roundedProgress + '%';

                // تأثيرات بصرية إضافية
                if (progress > 90) {
                    this.loadingPercentage.style.color = 'var(--accent-color)';
                    this.loadingPercentage.style.textShadow = '0 0 15px var(--accent-color)';
                }
            }

            completeLoading() {
                // تأثير الاكتمال
                this.loadingScreen.style.transform = 'scale(1.05)';

                setTimeout(() => {
                    this.loadingScreen.classList.add('fade-out');

                    // إزالة العنصر من DOM بعد الانتهاء من الانيميشن
                    setTimeout(() => {
                        if (this.loadingScreen && this.loadingScreen.parentNode) {
                            this.loadingScreen.remove();
                        }
                    }, 500);
                }, 100);
            }
        }

        // تشغيل مدير التحميل
        const loadingManager = new LoadingManager();
    </script>

    <!-- Lazy Loading Script -->
    <script>
        class LazyLoader {
            constructor() {
                this.images = [];
                this.imageObserver = null;
                this.init();
            }

            init() {
                // إنشاء Intersection Observer للصور
                this.createImageObserver();

                // العثور على جميع الصور القابلة للتحميل البطيء
                this.findLazyImages();

                // مراقبة الصور الجديدة التي قد تضاف لاحقاً
                this.observeNewImages();
            }

            createImageObserver() {
                if ('IntersectionObserver' in window) {
                    this.imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.loadImage(entry.target);
                                observer.unobserve(entry.target);
                            }
                        });
                    }, {
                        // بدء التحميل قبل 100px من دخول الصورة للشاشة
                        rootMargin: '100px 0px',
                        threshold: 0.01
                    });
                }
            }

            findLazyImages() {
                // البحث عن الصور مع data-src
                const lazyImages = document.querySelectorAll('img[data-src]');

                lazyImages.forEach(img => {
                    if (this.imageObserver) {
                        // إضافة كلاس التحميل
                        img.classList.add('lazy-loading');
                        this.imageObserver.observe(img);
                    } else {
                        // fallback للمتصفحات القديمة
                        this.loadImage(img);
                    }
                });
            }

            loadImage(img) {
                // إنشاء صورة مؤقتة للتحميل المسبق
                const tempImg = new Image();

                tempImg.onload = () => {
                    // تطبيق تأثير الظهور التدريجي
                    img.style.opacity = '0';
                    img.src = tempImg.src;

                    // إزالة data-src بعد التحميل
                    img.removeAttribute('data-src');

                    // تأثير الظهور
                    img.classList.remove('lazy-loading');
                    img.classList.add('lazy-loaded');

                    setTimeout(() => {
                        img.style.opacity = '1';
                    }, 50);
                };

                tempImg.onerror = () => {
                    // في حالة فشل التحميل
                    img.classList.remove('lazy-loading');
                    img.classList.add('lazy-error');

                    // صورة بديلة أو placeholder
                    img.src =
                        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjUgMTAwTDE1MCA3NUwxNzUgMTAwTDE2Mi41IDExMi41TDE1MCA5My43NUwxMzcuNSAxMTIuNUwxMjUgMTAwWiIgZmlsbD0iIzlDQTNBRiIvPgo8L3N2Zz4K';
                };

                // بدء تحميل الصورة
                tempImg.src = img.dataset.src;
            }

            observeNewImages() {
                // مراقبة إضافة صور جديدة للصفحة
                const mutationObserver = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType === 1) { // Element node
                                // البحث عن صور جديدة
                                const newImages = node.querySelectorAll ?
                                    node.querySelectorAll('img[data-src]') : [];

                                newImages.forEach(img => {
                                    if (this.imageObserver) {
                                        img.classList.add('lazy-loading');
                                        this.imageObserver.observe(img);
                                    } else {
                                        this.loadImage(img);
                                    }
                                });

                                // إذا كان العنصر نفسه صورة
                                if (node.tagName === 'IMG' && node.dataset.src) {
                                    if (this.imageObserver) {
                                        node.classList.add('lazy-loading');
                                        this.imageObserver.observe(node);
                                    } else {
                                        this.loadImage(node);
                                    }
                                }
                            }
                        });
                    });
                });

                mutationObserver.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }

            // دالة لتحويل الصور العادية إلى lazy loading
            convertToLazy(selector = 'img') {
                const images = document.querySelectorAll(selector);
                images.forEach(img => {
                    if (img.src && !img.dataset.src) {
                        img.dataset.src = img.src;
                        img.src =
                            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxjaXJjbGUgY3g9IjUwIiBjeT0iNTAiIHI9IjIwIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIgZmlsbD0ibm9uZSI+CjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgYXR0cmlidXRlVHlwZT0iWE1MIiB0eXBlPSJyb3RhdGUiIGZyb209IjAgNTAgNTAiIHRvPSIzNjAgNTAgNTAiIGR1cj0iMXMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIi8+CjwvY2lyY2xlPgo8L3N2Zz4K';

                        if (this.imageObserver) {
                            img.classList.add('lazy-loading');
                            this.imageObserver.observe(img);
                        } else {
                            this.loadImage(img);
                        }
                    }
                });
            }
        }

        // تشغيل Lazy Loading
        document.addEventListener('DOMContentLoaded', () => {
            const lazyLoader = new LazyLoader();

            // تحويل الصور الموجودة إلى lazy loading (اختياري)
            // lazyLoader.convertToLazy('img:not([data-src])');
        });
    </script>

    <!-- Page Progress Indicator Script -->
    <script>
        class PageProgressIndicator {
            constructor() {
                this.progressBar = document.getElementById('pageProgressBar');
                this.progressCircle = document.getElementById('progressCircle');
                this.progressText = document.getElementById('progressText');
                this.isScrolling = false;
                this.scrollTimeout = null;

                this.init();
            }

            init() {
                // تحديث التقدم عند التمرير
                window.addEventListener('scroll', this.throttle(this.updateProgress.bind(this), 16));

                // النقر على الدائرة للعودة للأعلى
                this.progressCircle.addEventListener('click', this.scrollToTop.bind(this));

                // تحديث أولي
                this.updateProgress();

                // إضافة تأثيرات إضافية
                this.addScrollEffects();
            }

            updateProgress() {
                const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrolled = window.scrollY;
                const progress = Math.min(Math.max((scrolled / windowHeight) * 100, 0), 100);

                // تحديث شريط التقدم
                this.progressBar.style.width = progress + '%';

                // تحديث الدائرة
                this.progressCircle.style.setProperty('--progress', progress);
                this.progressText.textContent = Math.round(progress) + '%';

                // إظهار/إخفاء الدائرة
                if (scrolled > 300) {
                    this.progressCircle.classList.add('visible');
                } else {
                    this.progressCircle.classList.remove('visible');
                }

                // تأثير النبض عند اكتمال التقدم
                if (progress >= 99) {
                    this.progressCircle.classList.add('completed');
                    this.addCompletionEffect();
                } else {
                    this.progressCircle.classList.remove('completed');
                }
            }

            scrollToTop() {
                const duration = 800;
                const start = window.scrollY;
                const startTime = performance.now();

                // تأثير الارتداد
                this.progressCircle.style.transform = 'translateY(-5px) scale(0.9)';
                setTimeout(() => {
                    this.progressCircle.style.transform = '';
                }, 150);

                const animateScroll = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // منحنى الحركة السلس (easeOutCubic)
                    const easeOutCubic = 1 - Math.pow(1 - progress, 3);

                    window.scrollTo(0, start * (1 - easeOutCubic));

                    if (progress < 1) {
                        requestAnimationFrame(animateScroll);
                    }
                };

                requestAnimationFrame(animateScroll);
            }

            addScrollEffects() {
                let lastScrollY = window.scrollY;

                window.addEventListener('scroll', () => {
                    const currentScrollY = window.scrollY;
                    const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';

                    // تأثير اتجاه التمرير على الدائرة
                    if (scrollDirection === 'down' && currentScrollY > 100) {
                        this.progressCircle.style.transform = 'translateY(0) scale(0.95)';
                    } else if (scrollDirection === 'up') {
                        this.progressCircle.style.transform = 'translateY(0) scale(1.05)';
                    }

                    // إعادة تعيين التحويل بعد فترة قصيرة
                    clearTimeout(this.scrollTimeout);
                    this.scrollTimeout = setTimeout(() => {
                        this.progressCircle.style.transform = '';
                    }, 150);

                    lastScrollY = currentScrollY;
                });
            }

            addCompletionEffect() {
                // تأثير الاكتمال - نبضة ذهبية
                this.progressBar.style.boxShadow = '0 0 20px var(--accent-color), 0 0 40px var(--accent-color)';

                setTimeout(() => {
                    this.progressBar.style.boxShadow = '0 0 10px var(--accent-color)';
                }, 500);
            }

            throttle(func, limit) {
                let inThrottle;
                return function() {
                    const args = arguments;
                    const context = this;
                    if (!inThrottle) {
                        func.apply(context, args);
                        inThrottle = true;
                        setTimeout(() => inThrottle = false, limit);
                    }
                }
            }
        }

        // تشغيل مؤشر التقدم عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', () => {
            new PageProgressIndicator();
        });
    </script>

    @stack('scripts')
    <style>
        /* CSS Variables - Smart Color System */
        :root {
            /* Primary Colors */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --primary-dark: #5a6fd8;

            /* Accent Colors */
            --accent-color: #ffd700;
            --accent-hover: #ffed4e;
            --accent-gradient: linear-gradient(135deg, #ffd700, #ffed4e);

            /* Neutral Colors */
            --white: #ffffff;
            --white-05: rgba(255, 255, 255, 0.05);
            --white-10: rgba(255, 255, 255, 0.1);
            --white-15: rgba(255, 255, 255, 0.15);
            --white-20: rgba(255, 255, 255, 0.2);
            --white-30: rgba(255, 255, 255, 0.3);
            --white-70: rgba(255, 255, 255, 0.7);
            --white-80: rgba(255, 255, 255, 0.8);
            --white-90: rgba(255, 255, 255, 0.9);

            /* Dark Colors */
            --dark-10: rgba(0, 0, 0, 0.1);
            --dark-15: rgba(0, 0, 0, 0.15);
            --dark-20: rgba(0, 0, 0, 0.2);

            /* Shadows */
            --shadow-sm: 0 4px 15px var(--dark-10);
            --shadow-md: 0 8px 25px var(--dark-15);
            --shadow-lg: 0 15px 35px var(--dark-20);

            /* Transitions */
            --transition-smooth: all 0.3s ease;
            --transition-fast: all 0.2s ease;

            /* Border Radius */
            --radius-sm: 10px;
            --radius-md: 15px;
            --radius-lg: 20px;
            --radius-xl: 25px;
            --radius-full: 50px;

            /* Spacing */
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-lg: 2rem;
            --space-xl: 3rem;

            /* Typography */
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --font-weight-black: 900;
        }

        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            overflow: hidden;
        }

        .loading-screen.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loading-container {
            text-align: center;
            color: var(--white);
            position: relative;
            z-index: 2;
        }

        .loading-logo {
            margin-bottom: var(--space-xl);
            animation: logoFloat 3s ease-in-out infinite;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--accent-gradient);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-sm);
            font-size: 2.5rem;
            color: #333;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, var(--white-30), transparent);
            animation: logoShimmer 2s ease-in-out infinite;
        }

        @keyframes logoShimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: var(--font-weight-black);
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: var(--space-sm);
        }

        .loading-progress {
            margin: var(--space-xl) 0;
            min-width: 300px;
        }

        .progress-bar-loading {
            width: 100%;
            height: 6px;
            background: var(--white-20);
            border-radius: var(--radius-sm);
            overflow: hidden;
            margin-bottom: var(--space-sm);
            position: relative;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: var(--accent-gradient);
            border-radius: var(--radius-sm);
            transition: width 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, var(--white-30), transparent);
            animation: progressShimmer 1.5s ease-in-out infinite;
        }

        @keyframes progressShimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .loading-text {
            font-size: 1rem;
            font-weight: var(--font-weight-medium);
            color: var(--white-90);
            margin-bottom: var(--space-xs);
        }

        .loading-percentage {
            font-size: 1.2rem;
            font-weight: var(--font-weight-bold);
            color: var(--accent-color);
            text-shadow: 0 0 10px var(--accent-color);
        }

        .loading-animation {
            margin-top: var(--space-xl);
        }

        .dot-pulse {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .dot-pulse div {
            width: 12px;
            height: 12px;
            background: var(--accent-color);
            border-radius: var(--radius-full);
            animation: dotPulse 1.4s ease-in-out infinite both;
        }

        .dot-pulse .dot1 {
            animation-delay: -0.32s;
        }

        .dot-pulse .dot2 {
            animation-delay: -0.16s;
        }

        .dot-pulse .dot3 {
            animation-delay: 0s;
        }

        @keyframes dotPulse {

            0%,
            80%,
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            40% {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        .loading-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-color);
            border-radius: var(--radius-full);
            opacity: 0.6;
        }

        .particle:nth-child(1) {
            top: 20%;
            left: 20%;
            animation: particleFloat 6s ease-in-out infinite;
        }

        .particle:nth-child(2) {
            top: 60%;
            left: 80%;
            animation: particleFloat 8s ease-in-out infinite reverse;
        }

        .particle:nth-child(3) {
            top: 40%;
            left: 10%;
            animation: particleFloat 7s ease-in-out infinite;
            animation-delay: -2s;
        }

        .particle:nth-child(4) {
            top: 80%;
            left: 60%;
            animation: particleFloat 9s ease-in-out infinite reverse;
            animation-delay: -1s;
        }

        .particle:nth-child(5) {
            top: 10%;
            left: 70%;
            animation: particleFloat 5s ease-in-out infinite;
            animation-delay: -3s;
        }

        @keyframes particleFloat {

            0%,
            100% {
                transform: translateY(0px) translateX(0px) scale(1);
                opacity: 0.6;
            }

            25% {
                transform: translateY(-20px) translateX(10px) scale(1.2);
                opacity: 1;
            }

            50% {
                transform: translateY(-10px) translateX(-15px) scale(0.8);
                opacity: 0.4;
            }

            75% {
                transform: translateY(-30px) translateX(5px) scale(1.1);
                opacity: 0.8;
            }
        }

        /* Page Progress Indicator */
        .page-progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .page-progress-bar {
            height: 100%;
            width: 0%;
            background: var(--accent-gradient);
            transition: width 0.3s ease;
            box-shadow: 0 0 10px var(--accent-color);
            position: relative;
        }

        .page-progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--accent-color));
            opacity: 0.8;
            animation: progressGlow 2s ease-in-out infinite;
        }

        @keyframes progressGlow {

            0%,
            100% {
                opacity: 0.8;
            }

            50% {
                opacity: 1;
            }
        }

        .progress-circle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: var(--radius-full);
            background: var(--white);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9998;
            cursor: pointer;
            transition: var(--transition-smooth);
            border: 3px solid transparent;
            background-clip: padding-box;
            backdrop-filter: blur(10px);
            opacity: 0;
            transform: translateY(100px);
        }

        .progress-circle.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .progress-circle:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .progress-circle::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: var(--radius-full);
            background: conic-gradient(var(--accent-color) 0deg,
                    var(--accent-color) calc(var(--progress, 0) * 3.6deg),
                    var(--white-20) calc(var(--progress, 0) * 3.6deg),
                    var(--white-20) 360deg);
            z-index: -1;
            transition: var(--transition-smooth);
        }

        .progress-text {
            font-size: 0.8rem;
            font-weight: var(--font-weight-bold);
            color: var(--primary-color);
            text-align: center;
            line-height: 1;
        }

        /* تأثيرات إضافية للمؤشر */
        .progress-circle.completed {
            animation: completionPulse 1s ease-in-out;
        }

        @keyframes completionPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
                box-shadow: 0 0 30px var(--accent-color);
            }

            100% {
                transform: scale(1);
            }
        }

        .page-progress-bar.completed {
            background: var(--accent-gradient);
            animation: completionShimmer 2s ease-in-out;
        }

        @keyframes completionShimmer {

            0%,
            100% {
                background: var(--accent-gradient);
                box-shadow: 0 0 10px var(--accent-color);
            }

            50% {
                background: linear-gradient(90deg, var(--accent-color), var(--white), var(--accent-color));
                box-shadow: 0 0 25px var(--accent-color);
            }
        }

        /* الاستجابة للشاشات المختلفة */
        @media (max-width: 768px) {
            .progress-circle {
                width: 50px;
                height: 50px;
                bottom: 20px;
                right: 20px;
            }

            .progress-text {
                font-size: 0.7rem;
            }

            .page-progress-container {
                height: 3px;
            }
        }

        @media (max-width: 480px) {
            .progress-circle {
                width: 45px;
                height: 45px;
                bottom: 15px;
                right: 15px;
            }

            .progress-text {
                font-size: 0.6rem;
            }

            .page-progress-container {
                height: 2px;
            }
        }

        /* تأثيرات تفاعلية إضافية */
        .progress-circle:active {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }

        .page-progress-bar.scrolling {
            transition: width 0.1s ease;
        }

        /* تحسين الأداء */
        .page-progress-container,
        .progress-circle {
            will-change: transform, opacity;
        }

        /* وضع الليل (اختياري للمستقبل) */
        @media (prefers-color-scheme: dark) {
            .page-progress-container {
                background: rgba(255, 255, 255, 0.1);
            }

            .progress-circle {
                background: rgba(255, 255, 255, 0.95);
            }
        }

        /* Modern Layout Styles */
        .modern-layout {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Navbar Styles */
        #mainNavbar {
            background: var(--primary-gradient);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-md);
            transition: var(--transition-smooth);
            padding: var(--space-sm) 0;
        }

        #mainNavbar.scrolled {
            background: rgba(102, 126, 234, 0.95);
            backdrop-filter: blur(20px);
            padding: var(--space-xs) 0;
        }

        .navbar-brand {
            font-weight: var(--font-weight-bold);
            font-size: 1.5rem;
            transition: var(--transition-smooth);
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .brand-icon {
            background: var(--accent-gradient);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #333;
            box-shadow: var(--shadow-sm);
        }

        .brand-text {
            background: linear-gradient(45deg, var(--white), #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: var(--font-weight-black);
        }

        .nav-link {
            font-weight: var(--font-weight-semibold);
            margin: 0 var(--space-xs);
            border-radius: var(--radius-xl);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--white-20), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px var(--white-20);
        }

        .navbar-nav .btn {
            border-radius: var(--radius-xl);
            font-weight: var(--font-weight-semibold);
            transition: var(--transition-smooth);
            border: 2px solid transparent;
        }

        .navbar-nav .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-light {
            border-color: var(--white-30);
        }

        .btn-outline-light:hover {
            background: var(--white-10);
            border-color: var(--white-30);
        }

        .btn-warning {
            background: var(--accent-gradient);
            border: none;
            color: #333;
            font-weight: var(--font-weight-bold);
        }

        .btn-warning:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* User Menu Styles */
        .user-avatar {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 0.9rem;
        }

        .modern-dropdown {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            margin-top: 0.5rem;
        }

        .modern-dropdown .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 0 0.5rem;
        }

        .modern-dropdown .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(-5px);
        }

        /* Main Content */
        .main-content {
            padding-top: 80px;
            min-height: calc(100vh - 80px);
        }

        /* Alert Styles */
        .modern-alert {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border-right: 4px solid;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-right-color: #28a745;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-right-color: #dc3545;
        }

        /* Footer Styles */
        .modern-footer {
            position: relative;
            background: var(--primary-gradient);
            color: var(--white);
            margin-top: 0px;
            overflow: hidden;
            padding-top: 0px;
        }

        .modern-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, var(--white-05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, var(--white-05) 0%, transparent 50%);
            animation: backgroundShift 30s ease-in-out infinite;
        }



        .footer-content {
            position: relative;
            z-index: 1;
            padding: 4rem 0 2rem;
        }

        .footer-brand .brand-icon-large {
            background: var(--accent-gradient);
            width: 60px;
            height: 60px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #333;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-smooth);
        }

        .footer-brand .brand-icon-large:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--white-10);
            border-radius: var(--radius-full);
            color: var(--white);
            text-decoration: none;
            transition: var(--transition-smooth);
            backdrop-filter: blur(10px);
            border: 1px solid var(--white-20);
        }

        .social-link:hover {
            background: var(--white-20);
            color: var(--accent-color);
            transform: translateY(-2px);
            border-color: var(--white-30);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links h6 {
            color: var(--white);
            font-weight: var(--font-weight-bold);
            margin-bottom: var(--space-sm);
            font-size: 1.1rem;
            position: relative;
        }

        .footer-links h6::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--accent-color);
            border-radius: var(--radius-sm);
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--white-80);
            text-decoration: none;
            transition: var(--transition-smooth);
            display: inline-block;
            font-weight: var(--font-weight-normal);
        }

        .footer-links a:hover {
            color: var(--white);
            transform: translateX(3px);
        }

        .newsletter-form .form-control {
            border: none;
            border-radius: var(--radius-xl) 0 0 var(--radius-xl);
            background: var(--white-10);
            backdrop-filter: blur(10px);
            color: var(--white);
            border: 1px solid var(--white-20);
            transition: var(--transition-smooth);
            padding: 0.75rem 1rem;
        }

        .newsletter-form .form-control:focus {
            background: var(--white-15);
            border-color: var(--white-30);
            box-shadow: 0 0 0 3px var(--white-10);
            outline: none;
        }

        .newsletter-form .form-control::placeholder {
            color: var(--white-70);
        }

        .newsletter-form .btn {
            border-radius: 0 var(--radius-xl) var(--radius-xl) 0;
            border: none;
            background: var(--accent-color);
            color: #333;
            font-weight: var(--font-weight-semibold);
            transition: var(--transition-smooth);
            padding: 0.75rem 1.5rem;
        }

        .newsletter-form .btn:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .footer-divider {
            border: none;
            margin: var(--space-lg) 0;
            height: 1px;
            background: var(--white-20);
        }

        .footer-bottom-links a {
            color: var(--white-70);
            text-decoration: none;
            transition: var(--transition-smooth);
            padding: 0.25rem var(--space-xs);
            font-size: 0.9rem;
        }

        .footer-bottom-links a:hover {
            color: var(--white);
        }

        .footer-bottom-links a::after {
            content: '•';
            color: var(--white-30);
            margin-right: var(--space-xs);
        }

        .footer-bottom-links a:last-child::after {
            display: none;
        }

        @keyframes backgroundShift {

            0%,
            100% {
                transform: translateY(0px);
                opacity: 0.05;
            }

            50% {
                transform: translateY(-10px);
                opacity: 0.1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .brand-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }


        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>

    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add Animation Classes on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.nav-link, .navbar-brand, .btn');
            animatedElements.forEach(el => observer.observe(el));
            const animatedElements = document.querySelectorAll('.nav-link, .navbar-brand, .btn');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>
