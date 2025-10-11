// DOM Elements
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const themeToggle = document.getElementById('themeToggle');
const body = document.body;

// Theme Management
class ThemeManager {
    constructor() {
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    init() {
        this.applyTheme(this.currentTheme);
        this.setupEventListeners();
    }

    applyTheme(theme) {
        body.setAttribute('data-theme', theme);

        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }
        }
    }

    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.currentTheme);
        localStorage.setItem('theme', this.currentTheme);
    }

    setupEventListeners() {
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }
}

// Sidebar Management
class SidebarManager {
    constructor() {
        this.isOpen = false;
        this.sidebar = document.getElementById('sidebar');
        this.sidebarClose = document.getElementById('sidebarClose');
        this.menuToggle = document.getElementById('menuToggle');
        this.overlay = document.getElementById('overlay');
        this.body = document.body;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.autoOpenSidebar();
        console.log('SidebarManager initialized');
    }

    autoOpenSidebar() {
        // Open sidebar automatically on desktop
        if (window.innerWidth > 768) {
            this.openSidebar();
        }
    }

    setupEventListeners() {
        if (this.menuToggle) {
            this.menuToggle.addEventListener('click', () => this.toggleSidebar());
            console.log('Menu toggle event listener added');
        }

        if (this.sidebarClose) {
            this.sidebarClose.addEventListener('click', () => this.closeSidebar());
        }

        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.closeSidebar());
        }

        // Close sidebar when clicking on menu items
        if (this.sidebar) {
            const menuItems = this.sidebar.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', () => this.closeSidebar());
            });
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeSidebar();
            }
        });

        // Add search functionality
        this.setupSearch();


        // Add wallet functionality
        this.setupWallet();
    }

    setupSearch() {
        const searchInput = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.search-btn');

        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', () => this.performSearch());
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.performSearch();
                }
            });
        }
    }

    performSearch() {
        const searchInput = document.querySelector('.search-input');
        const query = searchInput.value.trim();

        if (query) {
            console.log('Searching for:', query);
            // Add search functionality here
            this.showSearchResults(query);
        }
    }

    showSearchResults(query) {
        // Implement search results display
        console.log('Search results for:', query);
    }


    setupWallet() {
        const walletBtn = document.getElementById('walletBtn');

        if (walletBtn) {
            walletBtn.addEventListener('click', () => this.showWallet());
        }
    }

    showWallet() {
        console.log('Showing wallet');
        // Implement wallet dropdown or redirect
        window.location.href = '/wallet';
    }

    toggleSidebar() {
        if (this.isOpen) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    }

    openSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.add('active');
            console.log('Sidebar opened');
        }

        if (this.overlay) {
            this.overlay.classList.add('active');
        }

        this.body.classList.add('sidebar-open');
        this.isOpen = true;

        // Animate menu toggle
        this.animateMenuToggle(true);

        // Add progress bar animation
        this.animateProgressBar();
    }

    closeSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.remove('active');
            console.log('Sidebar closed');
        }

        if (this.overlay) {
            this.overlay.classList.remove('active');
        }

        this.body.classList.remove('sidebar-open');
        this.isOpen = false;

        // Animate menu toggle
        this.animateMenuToggle(false);
    }

    animateMenuToggle(isOpen) {
        if (this.menuToggle) {
            const spans = this.menuToggle.querySelectorAll('span');

            if (isOpen) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        }
    }

    animateProgressBar() {
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            progressBar.style.width = '100%';
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 2000);
        }
    }
}

// Hero Slider
class HeroSlider {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.slide');
        this.autoSlideInterval = null;
        this.init();
    }

    init() {
        if (this.slides.length > 0) {
            this.showSlide(0);
            this.startAutoSlide();
        }
    }

    showSlide(index) {
        this.slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        this.currentSlide = index;
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(nextIndex);
    }

    startAutoSlide() {
        this.autoSlideInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    }

    stopAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
        }
    }
}

// Animation Observer
class AnimationObserver {
    constructor() {
        this.init();
    }

    init() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        this.observeElements();
    }

    observeElements() {
        const elements = document.querySelectorAll('.category-card, .offer-card');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            this.observer.observe(element);
        });
    }
}


// Smooth Scrolling
class SmoothScrolling {
    constructor() {
        this.init();
    }

    init() {
        // Add smooth scrolling to all anchor links
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// Performance Optimizer
class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.lazyLoadImages();
        this.optimizeAnimations();
    }

    lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }

    optimizeAnimations() {
        // Reduce animations on low-end devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--transition', 'none');
        }
    }
}

// Utility Functions
class Utils {
    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    static throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    static formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    static formatDate(date) {
        return new Intl.DateTimeFormat('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }
}

// Error Handler
class ErrorHandler {
    constructor() {
        this.init();
    }

    init() {
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            this.logError(event.error);
        });

        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            this.logError(event.reason);
        });
    }

    logError(error) {
        // In a real application, you would send this to a logging service
        console.error('Error logged:', {
            message: error.message,
            stack: error.stack,
            timestamp: new Date().toISOString()
        });
    }
}

// PWA Install Manager
class PWAInstallManager {
    constructor() {
        this.deferredPrompt = null;
        this.installPrompt = document.getElementById('installPrompt');
        this.installBtn = document.getElementById('installBtn');
        this.dismissBtn = document.getElementById('dismissBtn');
        this.init();
    }

    init() {
        if (this.installPrompt || this.installBtn || this.dismissBtn) {
            this.setupEventListeners();
            this.checkInstallability();
        }
    }

    setupEventListeners() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallPrompt();
        });

        if (this.installBtn) {
            this.installBtn.addEventListener('click', () => this.installApp());
        }
        if (this.dismissBtn) {
            this.dismissBtn.addEventListener('click', () => this.hideInstallPrompt());
        }

        window.addEventListener('appinstalled', () => {
            this.hideInstallPrompt();
            console.log('PWA was installed');
        });
    }

    showInstallPrompt() {
        if (this.installPrompt && !localStorage.getItem('installPromptDismissed')) {
            setTimeout(() => {
                if (this.installPrompt) {
                    this.installPrompt.classList.add('show');
                }
            }, 3000);
        }
    }

    hideInstallPrompt() {
        if (this.installPrompt) {
            this.installPrompt.classList.remove('show');
            localStorage.setItem('installPromptDismissed', 'true');
        }
    }

    async installApp() {
        if (this.deferredPrompt) {
            try {
                this.deferredPrompt.prompt();
                const { outcome } = await this.deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                this.deferredPrompt = null;
                this.hideInstallPrompt();
            } catch (error) {
                console.error('Error during app installation:', error);
            }
        }
    }

    checkInstallability() {
        if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) {
            // App is already installed
            this.hideInstallPrompt();
        }
    }
}

// Innovative Offers System
class InnovativeOffersSystem {
    constructor() {
        this.init();
    }

    init() {
        this.setup3DCards();
        this.setupHolographicCards();
        this.setupGlassmorphismCards();
        this.setupNeonCards();
        this.setupMorphingCards();
        this.setupFloatingCards();
        this.setupCarouselControls();
        this.setupMagicButton();
    }

    setup3DCards() {
        const cards3D = document.querySelectorAll('.offer-card-3d');
        cards3D.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'perspective(1000px) rotateX(5deg) rotateY(5deg) scale(1.05)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale(1)';
            });
        });
    }

    setupHolographicCards() {
        const holoCards = document.querySelectorAll('.offer-card-holographic');
        holoCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            });
        });
    }

    setupGlassmorphismCards() {
        const glassCards = document.querySelectorAll('.offer-card-glass');
        glassCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                card.style.boxShadow = `0 ${8 + Math.abs(rotateX)}px ${32 + Math.abs(rotateY)}px rgba(0, 0, 0, 0.1)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
                card.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.1)';
            });
        });
    }

    setupNeonCards() {
        const neonCards = document.querySelectorAll('.offer-card-neon');
        neonCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.boxShadow = '0 0 30px rgba(0, 212, 255, 0.6), 0 0 60px rgba(0, 212, 255, 0.4)';
                card.style.transform = 'scale(1.02)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.boxShadow = '0 0 20px rgba(0, 212, 255, 0.3)';
                card.style.transform = 'scale(1)';
            });
        });
    }

    setupMorphingCards() {
        const morphCards = document.querySelectorAll('.offer-card-morph');
        morphCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'scale(1.05) rotate(2deg)';
                card.style.filter = 'brightness(1.1)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'scale(1) rotate(0deg)';
                card.style.filter = 'brightness(1)';
            });
        });
    }

    setupFloatingCards() {
        const floatCards = document.querySelectorAll('.offer-card-float');
        floatCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.animationPlayState = 'paused';
                card.style.transform = 'translateY(-15px) scale(1.02)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.animationPlayState = 'running';
                card.style.transform = 'translateY(0px) scale(1)';
            });
        });
    }

    setupCarouselControls() {
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const carousel = document.querySelector('.offers-carousel');

        if (prevBtn && nextBtn && carousel) {
            let currentIndex = 0;
            const cards = carousel.children;
            const totalCards = cards.length;

            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalCards) % totalCards;
                this.updateCarousel(carousel, currentIndex);
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalCards;
                this.updateCarousel(carousel, currentIndex);
            });
        }
    }

    updateCarousel(carousel, index) {
        const cards = carousel.children;
        cards.forEach((card, i) => {
            if (i === index) {
                card.style.transform = 'scale(1.05)';
                card.style.zIndex = '10';
            } else {
                card.style.transform = 'scale(1)';
                card.style.zIndex = '1';
            }
        });
    }

    setupMagicButton() {
        const magicBtn = document.querySelector('.btn-magic');
        if (magicBtn) {
            magicBtn.addEventListener('click', () => {
                this.createMagicEffect(magicBtn);
            });
        }
    }

    createMagicEffect(button) {
        // Create sparkle effect
        for (let i = 0; i < 20; i++) {
            setTimeout(() => {
                const sparkle = document.createElement('div');
                sparkle.style.position = 'absolute';
                sparkle.style.width = '4px';
                sparkle.style.height = '4px';
                sparkle.style.background = 'white';
                sparkle.style.borderRadius = '50%';
                sparkle.style.pointerEvents = 'none';
                sparkle.style.left = Math.random() * 100 + '%';
                sparkle.style.top = Math.random() * 100 + '%';
                sparkle.style.animation = 'sparkle 1s ease-out forwards';

                button.appendChild(sparkle);

                setTimeout(() => {
                    sparkle.remove();
                }, 1000);
            }, i * 50);
        }
    }
}

// Initialize Application
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all components
    if (typeof ThemeManager !== 'undefined') {
        new ThemeManager();
    }
    if (typeof SidebarManager !== 'undefined') {
        const sidebarManager = new SidebarManager();
    }
    if (typeof HeroSlider !== 'undefined') {
        new HeroSlider();
    }
    if (typeof AnimationObserver !== 'undefined') {
        new AnimationObserver();
    }
    if (typeof SmoothScrolling !== 'undefined') {
        new SmoothScrolling();
    }
    if (typeof PerformanceOptimizer !== 'undefined') {
        new PerformanceOptimizer();
    }
    if (typeof ErrorHandler !== 'undefined') {
        new ErrorHandler();
    }
    if (typeof PWAInstallManager !== 'undefined') {
        new PWAInstallManager();
    }
    if (typeof InnovativeOffersSystem !== 'undefined') {
        new InnovativeOffersSystem();
    }

    // Add loading animation
    document.body.classList.add('loaded');

    // Initialize any additional features
    initializeAdditionalFeatures();

    // Initialize mobile-specific features
    initializeMobileFeatures();

    // Auto-open sidebar on desktop
    setTimeout(() => {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && window.innerWidth > 768) {
            sidebar.classList.add('active');
            document.body.classList.add('sidebar-open');
            console.log('Sidebar auto-opened on desktop');
        }
    }, 500);
});

// Mobile-Specific Features
function initializeMobileFeatures() {
    // Add mobile touch optimizations
    addMobileTouchOptimizations();

    // Add mobile gesture support
    addMobileGestures();

    // Add mobile performance optimizations
    addMobilePerformanceOptimizations();

    // Add mobile UI enhancements
    addMobileUIEnhancements();

    // Add mobile accessibility features
    addMobileAccessibility();
}

// Additional Features
function initializeAdditionalFeatures() {
    // Add ripple effect to buttons
    addRippleEffect();
}

function addRippleEffect() {
    const buttons = document.querySelectorAll('.menu-item, .category-card, .theme-toggle');

    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Mobile Touch Optimizations
function addMobileTouchOptimizations() {
    // Prevent zoom on double tap for better mobile experience
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Improve touch scrolling performance
    document.addEventListener('touchstart', function() {}, { passive: true });
    document.addEventListener('touchmove', function() {}, { passive: true });
    document.addEventListener('touchend', function() {}, { passive: true });

    // Add touch feedback to interactive elements
    const touchElements = document.querySelectorAll('.btn, .menu-item, .service-card, .offer-card, .payment-item');
    touchElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });

        element.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });

        element.addEventListener('touchcancel', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Mobile Gestures
function addMobileGestures() {
    let startX = 0;
    let startY = 0;
    let startTime = 0;

    document.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        startTime = Date.now();
    });

    document.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const endY = e.changedTouches[0].clientY;
        const endTime = Date.now();
        const diffX = startX - endX;
        const diffY = startY - endY;
        const diffTime = endTime - startTime;

        // Only process quick swipes
        if (diffTime < 300) {
            handleSwipeGesture(diffX, diffY);
        }
    });

    function handleSwipeGesture(diffX, diffY) {
        const sidebarManager = new SidebarManager();

        // Horizontal swipes
        if (Math.abs(diffX) > 50 && Math.abs(diffY) < 100) {
            if (diffX < -50) {
                // Swipe right to open sidebar
                sidebarManager.openSidebar();
            } else if (diffX > 50) {
                // Swipe left to close sidebar
                sidebarManager.closeSidebar();
            }
        }

        // Vertical swipes
        if (Math.abs(diffY) > 50 && Math.abs(diffX) < 100) {
            if (diffY > 50) {
                // Swipe up for scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (diffY < -50) {
                // Swipe down for scroll to bottom
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }
        }
    }
}

// Mobile Performance Optimizations
function addMobilePerformanceOptimizations() {
    // Optimize scroll performance with throttling
    let ticking = false;

    function updateOnScroll() {
        // Scroll-based animations and effects
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateOnScroll);
            ticking = true;
        }
    }, { passive: true });

    // Reduce animations on low-end devices
    if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
        document.documentElement.style.setProperty('--transition', 'none');
        document.documentElement.style.setProperty('--animation-duration', '0.1s');
    }

    // Optimize images for mobile
    optimizeImages();
}

function optimizeImages() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.loading = 'lazy';
        img.decoding = 'async';

        // Add error handling
        img.addEventListener('error', function() {
            this.style.display = 'none';
        });
    });
}

// Mobile UI Enhancements
function addMobileUIEnhancements() {
    // Add mobile-specific classes
    if (window.innerWidth <= 768) {
        document.body.classList.add('mobile-device');
    }

    // Handle orientation change
    handleOrientationChange();

    // Add mobile menu enhancements
    enhanceMobileMenu();

    // Add mobile search functionality
    enhanceMobileSearch();
}

function handleOrientationChange() {
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 100);
    });
}

function enhanceMobileMenu() {
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    }
}

function enhanceMobileSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    }
}

// Mobile Accessibility
function addMobileAccessibility() {
    // Ensure minimum touch target sizes
    ensureTouchTargetSizes();

    // Add keyboard navigation support
    addKeyboardNavigationSupport();

    // Add screen reader support
    addScreenReaderSupport();
}

function ensureTouchTargetSizes() {
    const touchTargets = document.querySelectorAll('.btn, .menu-item, .payment-item');
    touchTargets.forEach(target => {
        if (target.offsetHeight < 44 || target.offsetWidth < 44) {
            target.style.minHeight = '44px';
            target.style.minWidth = '44px';
        }
    });
}

function addKeyboardNavigationSupport() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });

    document.addEventListener('mousedown', () => {
        document.body.classList.remove('keyboard-navigation');
    });
}

function addScreenReaderSupport() {
    const screenReaderOnly = document.createElement('div');
    screenReaderOnly.setAttribute('aria-live', 'polite');
    screenReaderOnly.setAttribute('aria-atomic', 'true');
    screenReaderOnly.className = 'sr-only';
    screenReaderOnly.style.cssText = 'position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;';
    document.body.appendChild(screenReaderOnly);

    // Global function for screen reader announcements
    window.announceToScreenReader = function(message) {
        screenReaderOnly.textContent = message;
        setTimeout(() => {
            screenReaderOnly.textContent = '';
        }, 1000);
    };
}

// Export for use in other modules
window.KaymnMarket = {
    ThemeManager,
    SidebarManager,
    HeroSlider,
    AnimationObserver,
    InnovativeOffersSystem,
    Utils
};
